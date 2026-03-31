<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function create(): View
    {
        return view('pos.customers-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'state' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'gstin' => ['nullable', 'string', 'max:30'],
        ]);

        $data['customer_code'] = $this->generateCustomerCode($data['name']);

        Customer::create($data);

        return redirect()->route('pos.customers')->with('success', 'Customer created successfully.');
    }

    public function index(): View
    {
        $search = request('search');

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('customer_code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('mobile', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pos.customers-index', compact('customers'));
    }

    public function edit(Customer $customer): View
    {
        return view('pos.customers-edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'state' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'gstin' => ['nullable', 'string', 'max:30'],
        ]);

        $customer->update($data);

        return redirect()->route('pos.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('pos.customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function summary(): View
    {
        $customerId = request('customer_id');
        $customerSearch = trim((string) request('customer_search'));

        $customers = Customer::orderBy('name')->get(['id', 'name', 'customer_code']);

        $invoiceTotals = Invoice::query()
            ->select('customer_id', DB::raw('SUM(total_amount) as total_invoice'))
            ->groupBy('customer_id');

        $paymentTotals = Payment::query()
            ->select('customer_id', DB::raw('SUM(amount) as total_payment'))
            ->groupBy('customer_id');

        $rows = Customer::query()
            ->leftJoinSub($invoiceTotals, 'it', function ($join) {
                $join->on('customers.id', '=', 'it.customer_id');
            })
            ->leftJoinSub($paymentTotals, 'pt', function ($join) {
                $join->on('customers.id', '=', 'pt.customer_id');
            })
            ->when($customerId, fn ($q) => $q->where('customers.id', $customerId))
            ->when($customerSearch !== '', function ($q) use ($customerSearch) {
                $q->where(function ($sub) use ($customerSearch) {
                    $sub->where('customers.name', 'like', '%' . $customerSearch . '%')
                        ->orWhere('customers.customer_code', 'like', '%' . $customerSearch . '%');
                });
            })
            ->orderBy('customers.name')
            ->paginate(15, [
                'customers.id',
                'customers.customer_code',
                'customers.name',
                DB::raw('COALESCE(it.total_invoice, 0) as total_invoice'),
                DB::raw('COALESCE(pt.total_payment, 0) as total_payment'),
            ])
            ->withQueryString();

        return view('pos.customers-summary', compact('rows', 'customers'));
    }

    public function summaryDetails(Customer $customer): View
    {
        $invoices = Invoice::query()
            ->where('customer_id', $customer->id)
            ->latest('invoice_date')
            ->latest('id')
            ->get(['id', 'invoice_no', 'invoice_date', 'total_amount', 'status']);

        $payments = Payment::query()
            ->with('paymentMode:id,name')
            ->where('customer_id', $customer->id)
            ->latest('payment_date')
            ->latest('id')
            ->get(['id', 'customer_id', 'payment_mode_id', 'payment_date', 'amount', 'note']);

        $totalInvoice = (float) $invoices->sum('total_amount');
        $totalPayment = (float) $payments->sum('amount');

        return view('pos.customers-summary-details', compact('customer', 'invoices', 'payments', 'totalInvoice', 'totalPayment'));
    }

    private function generateCustomerCode(string $name): string
    {
        preg_match('/[A-Za-z]/', $name, $matches);
        $prefix = strtoupper($matches[0] ?? 'C');

        $maxNumber = Customer::query()
            ->where('customer_code', 'like', $prefix . '%')
            ->pluck('customer_code')
            ->map(static fn (string $code): int => (int) substr($code, 1))
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;
        $candidate = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        while (Customer::where('customer_code', $candidate)->exists()) {
            $nextNumber++;
            $candidate = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $candidate;
    }
}
