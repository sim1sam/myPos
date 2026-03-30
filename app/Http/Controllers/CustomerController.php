<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
