<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('pos.purchases-create', compact('vendors'));
    }

    public function store(Request $request): RedirectResponse
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

        Purchase::create($data);

        return redirect()->route('pos.purchases.create')->with('success', 'Purchase created successfully.');
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

        return view('pos.purchases-edit', compact('purchase', 'vendors'));
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
