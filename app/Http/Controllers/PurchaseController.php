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

        return redirect()->route('pos.purchases')->with('success', 'Purchase created successfully.');
    }

    public function index(): View
    {
        $purchases = Purchase::with('vendor')->latest()->paginate(15);

        return view('pos.purchases-index', compact('purchases'));
    }
}
