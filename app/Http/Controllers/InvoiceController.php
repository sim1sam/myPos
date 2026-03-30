<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Purchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function dashboard(): View
    {
        return view('pos.invoices');
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $products = $this->purchaseProducts();

        return view('pos.invoices-create', compact('customers', 'products'));
    }

    public function index(): View
    {
        $scope = request('scope', 'all');
        $pageTitle = $scope === 'gst' ? 'GST Invoices' : 'All Invoices';
        $customerId = request('customer_id');
        $status = request('status');
        $from = request('from');
        $to = request('to');
        $customers = Customer::orderBy('name')->get(['id', 'name', 'customer_code']);

        $invoices = Invoice::with('customer')
            ->when($scope === 'gst', fn ($q) => $q->where('gst_type', '!=', 'none'))
            ->when($customerId, fn ($q) => $q->where('customer_id', $customerId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($from, fn ($q) => $q->whereDate('invoice_date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('invoice_date', '<=', $to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pos.invoices-index', compact('scope', 'pageTitle', 'invoices', 'customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'prefix' => ['nullable', 'string', 'max:20'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'gst_type' => ['required', 'in:same,other,none'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'sgst' => ['required', 'numeric', 'min:0'],
            'cgst' => ['required', 'numeric', 'min:0'],
            'igst' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'items_json' => ['required', 'string'],
        ]);

        $items = json_decode($data['items_json'], true);
        if (!is_array($items) || count($items) === 0) {
            return back()->withErrors(['items_json' => 'Please add at least one invoice item.'])->withInput();
        }

        $validItems = collect($items)->map(function ($item) {
            return [
                'description' => trim((string) ($item['description'] ?? '')),
                'hsn_sac' => trim((string) ($item['hsn_sac'] ?? '')),
                'rate' => (float) ($item['rate'] ?? 0),
                'qty' => (int) ($item['qty'] ?? 0),
                'unit' => trim((string) ($item['unit'] ?? '')),
                'discount' => (float) ($item['discount'] ?? 0),
                'amount' => (float) ($item['amount'] ?? 0),
            ];
        })->filter(fn ($i) => $i['description'] !== '' && $i['qty'] > 0 && $i['amount'] >= 1)->values();

        if ($validItems->isEmpty()) {
            return back()->withErrors(['items_json' => 'Please provide valid item rows (minimum item amount is 1).'])->withInput();
        }

        $invoice = DB::transaction(function () use ($data, $validItems) {
            $hasHsnItems = $validItems->contains(fn ($item) => $item['hsn_sac'] !== '');
            $gstType = $hasHsnItems && $data['gst_type'] === 'none' ? 'same' : $data['gst_type'];

            $invoice = Invoice::create([
                'invoice_no' => $this->generateInvoiceNo(),
                'prefix' => $data['prefix'] ?: null,
                'customer_id' => $data['customer_id'] ?: null,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'gst_type' => $gstType,
                'status' => 'unpaid',
                'subtotal' => $data['subtotal'],
                'sgst' => $data['sgst'],
                'cgst' => $data['cgst'],
                'igst' => $data['igst'],
                'total_amount' => $data['total_amount'],
            ]);

            $invoice->items()->createMany($validItems->all());

            return $invoice;
        });

        return redirect()->route('pos.invoices.index')->with('success', "Invoice {$invoice->invoice_no} created successfully.");
    }

    private function generateInvoiceNo(): string
    {
        $prefix = now()->format('ymd');
        $lastForToday = Invoice::query()
            ->where('invoice_no', 'like', $prefix . '%')
            ->orderByDesc('invoice_no')
            ->value('invoice_no');

        $nextSequence = 1;
        if ($lastForToday) {
            $lastSeq = (int) substr($lastForToday, -3);
            $nextSequence = $lastSeq + 1;
        }

        return $prefix . str_pad((string) $nextSequence, 3, '0', STR_PAD_LEFT);
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('customer', 'items');

        return view('pos.invoices-show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');
        $customers = Customer::orderBy('name')->get();
        $products = $this->purchaseProducts();

        return view('pos.invoices-edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validate([
            'prefix' => ['nullable', 'string', 'max:20'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'gst_type' => ['required', 'in:same,other,none'],
            'status' => ['required', 'in:unpaid,paid,cancelled'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'sgst' => ['required', 'numeric', 'min:0'],
            'cgst' => ['required', 'numeric', 'min:0'],
            'igst' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'items_json' => ['required', 'string'],
        ]);

        $validItems = $this->validatedInvoiceItems($data['items_json']);
        if ($validItems->isEmpty()) {
            return back()->withErrors(['items_json' => 'Please provide valid item rows (minimum item amount is 1).'])->withInput();
        }

        DB::transaction(function () use ($invoice, $data, $validItems) {
            $hasHsnItems = $validItems->contains(fn ($item) => $item['hsn_sac'] !== '');
            $gstType = $hasHsnItems && $data['gst_type'] === 'none' ? 'same' : $data['gst_type'];

            $invoice->update([
                'prefix' => $data['prefix'] ?: null,
                'customer_id' => $data['customer_id'] ?: null,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'gst_type' => $gstType,
                'status' => $data['status'],
                'subtotal' => $data['subtotal'],
                'sgst' => $data['sgst'],
                'cgst' => $data['cgst'],
                'igst' => $data['igst'],
                'total_amount' => $data['total_amount'],
            ]);

            $invoice->items()->delete();
            $invoice->items()->createMany($validItems->all());
        });

        return redirect()->route('pos.invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('pos.invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function convertGst(Invoice $invoice): RedirectResponse
    {
        if ($invoice->gst_type === 'none') {
            $invoice->update(['gst_type' => 'same']);
        }

        return redirect()->route('pos.invoices.index', ['scope' => 'gst'])->with('success', 'Invoice converted to GST invoice.');
    }

    private function purchaseProducts()
    {
        return Purchase::query()
            ->whereNotNull('hsn_sac')
            ->where('hsn_sac', '!=', '')
            ->whereNotNull('product_name')
            ->where('product_name', '!=', '')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get([
                'id',
                'product_name',
                'hsn_sac',
                'price',
                'created_at',
            ])
            ->map(static function (Purchase $purchase): array {
                return [
                    'id' => $purchase->id,
                    'name' => $purchase->product_name,
                    'hsn_sac' => $purchase->hsn_sac,
                    'rate' => $purchase->price,
                    'unit' => '',
                    'created_at' => optional($purchase->created_at)->toDateTimeString(),
                ];
            })
            ->values();
    }

    private function validatedInvoiceItems(string $itemsJson)
    {
        $items = json_decode($itemsJson, true);
        if (!is_array($items) || count($items) === 0) {
            return collect();
        }

        return collect($items)->map(function ($item) {
            return [
                'description' => trim((string) ($item['description'] ?? '')),
                'hsn_sac' => trim((string) ($item['hsn_sac'] ?? '')),
                'rate' => (float) ($item['rate'] ?? 0),
                'qty' => (int) ($item['qty'] ?? 0),
                'unit' => trim((string) ($item['unit'] ?? '')),
                'discount' => (float) ($item['discount'] ?? 0),
                'amount' => (float) ($item['amount'] ?? 0),
            ];
        })->filter(fn ($i) => $i['description'] !== '' && $i['qty'] > 0 && $i['amount'] >= 1)->values();
    }
}
