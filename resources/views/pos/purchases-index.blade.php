@extends('layouts.pos-app')

@section('title', 'List Purchases — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Purchase List</h1>
            <a href="{{ route('pos.purchases.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create Purchase</a>
        </div>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Vendor</th>
                            <th class="px-4 py-3 font-semibold">Invoice No</th>
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">HSN/SAC</th>
                            <th class="px-4 py-3 font-semibold">Price</th>
                            <th class="px-4 py-3 font-semibold">Qty</th>
                            <th class="px-4 py-3 font-semibold">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td class="px-4 py-3">{{ $purchase->vendor?->name ?: $purchase->vendor_name }}</td>
                                <td class="px-4 py-3">{{ $purchase->invoice_no }}</td>
                                <td class="px-4 py-3">{{ $purchase->invoice_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $purchase->product_name }}</td>
                                <td class="px-4 py-3">{{ $purchase->hsn_sac ?: '-' }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $purchase->price, 2) }}</td>
                                <td class="px-4 py-3">{{ $purchase->qty }}</td>
                                <td class="px-4 py-3 font-semibold text-sky-700">Rs {{ number_format((float) $purchase->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">No purchases found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $purchases->links() }}
        </div>
    </section>
@endsection
