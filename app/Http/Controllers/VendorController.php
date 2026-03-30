<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function dashboard(): View
    {
        return view('pos.vendors');
    }

    public function create(): View
    {
        return view('pos.vendors-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gstin' => ['nullable', 'string', 'max:30'],
            'mobile_no' => ['nullable', 'string', 'max:30'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'account_no' => ['nullable', 'string', 'max:50'],
            'ifsc' => ['nullable', 'string', 'max:30'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'gpay_phonepay_no' => ['nullable', 'string', 'max:30'],
        ]);

        $data['vendor_code'] = $this->generateVendorCode($data['name']);

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('vendors/documents', 'public');
        }

        unset($data['document']);

        Vendor::create($data);

        return redirect()->route('pos.vendors')->with('success', 'Vendor created successfully.');
    }

    public function index(): View
    {
        $search = request('search');

        $vendors = Vendor::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('vendor_code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('gstin', 'like', '%' . $search . '%')
                        ->orWhere('mobile_no', 'like', '%' . $search . '%')
                        ->orWhere('bank_name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pos.vendors-index', compact('vendors'));
    }

    private function generateVendorCode(string $name): string
    {
        preg_match('/[A-Za-z]/', $name, $matches);
        $prefix = strtoupper($matches[0] ?? 'V');

        $maxNumber = Vendor::query()
            ->where('vendor_code', 'like', $prefix . '%')
            ->pluck('vendor_code')
            ->map(static fn (string $code): int => (int) substr($code, 1))
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;
        $candidate = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        while (Vendor::where('vendor_code', $candidate)->exists()) {
            $nextNumber++;
            $candidate = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $candidate;
    }
}
