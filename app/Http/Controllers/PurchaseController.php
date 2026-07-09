<?php

namespace App\Http\Controllers;

use App\Models\GstRate;
use App\Models\Purchase;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function dashboard(): View
    {
        return view('pos.purchases');
    }

    public function create(): View
    {
        $vendors = Vendor::orderBy('name')->get();
        $gstRates = GstRate::query()
            ->orderBy('hsn_sac')
            ->get(['hsn_sac', 'description']);
        $hsnSacOptions = $gstRates->pluck('hsn_sac');
        $hsnDescriptions = $gstRates->mapWithKeys(fn ($rate) => [$rate->hsn_sac => (string) ($rate->description ?? '')]);

        return view('pos.purchases-create', compact('vendors', 'hsnSacOptions', 'hsnDescriptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'invoice_no' => ['required', 'string', 'max:255'],
            'invoice_date' => ['required', 'date'],
            'items_json' => ['required', 'string'],
        ]);

        $items = json_decode($data['items_json'], true);
        if (! is_array($items) || count($items) === 0) {
            return back()->withErrors(['items_json' => 'Please add at least one purchase item.'])->withInput();
        }

        $validItems = collect($items)->map(function ($item) {
            return [
                'product_name' => trim((string) ($item['product_name'] ?? '')),
                'hsn_sac' => trim((string) ($item['hsn_sac'] ?? '')) ?: null,
                'price' => (float) ($item['price'] ?? 0),
                'qty' => (int) ($item['qty'] ?? 0),
            ];
        })->filter(fn ($item) => $item['product_name'] !== '' && $item['qty'] >= 1 && $item['price'] >= 0)->values();

        if ($validItems->isEmpty()) {
            return back()->withErrors(['items_json' => 'Please provide valid item rows with product name, price, and quantity.'])->withInput();
        }

        $vendor = Vendor::findOrFail($data['vendor_id']);

        DB::transaction(function () use ($data, $validItems, $vendor) {
            foreach ($validItems as $item) {
                Purchase::create([
                    'vendor_id' => $data['vendor_id'],
                    'vendor_name' => $vendor->name,
                    'invoice_no' => $data['invoice_no'],
                    'invoice_date' => $data['invoice_date'],
                    'product_name' => $item['product_name'],
                    'hsn_sac' => $item['hsn_sac'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'total_amount' => $item['price'] * $item['qty'],
                ]);
            }
        });

        $count = $validItems->count();
        $message = $count === 1
            ? 'Purchase created successfully.'
            : "{$count} purchase items created successfully.";

        return redirect()->route('pos.purchases.create')->with('success', $message);
    }

    public function index(): View
    {
        $search = request('search');

        $purchases = Purchase::with('vendor')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('id', 'like', '%' . $search . '%')
                        ->orWhere('vendor_name', 'like', '%' . $search . '%')
                        ->orWhere('invoice_no', 'like', '%' . $search . '%')
                        ->orWhere('product_name', 'like', '%' . $search . '%')
                        ->orWhere('hsn_sac', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pos.purchases-index', compact('purchases'));
    }

    public function edit(Purchase $purchase): View
    {
        $vendors = Vendor::orderBy('name')->get();
        $gstRates = GstRate::query()
            ->orderBy('hsn_sac')
            ->get(['hsn_sac', 'description']);
        $hsnSacOptions = $gstRates->pluck('hsn_sac');
        $hsnDescriptions = $gstRates->mapWithKeys(fn ($rate) => [$rate->hsn_sac => (string) ($rate->description ?? '')]);

        return view('pos.purchases-edit', compact('purchase', 'vendors', 'hsnSacOptions', 'hsnDescriptions'));
    }

    public function update(Request $request, Purchase $purchase): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'invoice_no' => ['required', 'string', 'max:255'],
            'invoice_date' => ['required', 'date'],
            'product_name' => ['required', 'string', 'max:255'],
            'hsn_sac' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $vendor = Vendor::findOrFail($data['vendor_id']);
        $data['vendor_name'] = $vendor->name;
        $data['total_amount'] = (float) $data['price'] * (int) $data['qty'];

        $purchase->update($data);

        return redirect()->route('pos.purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        $purchase->delete();

        return redirect()->route('pos.purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
