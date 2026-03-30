@extends('layouts.pos-app')

@section('title', 'List Purchases — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Purchase List</h1>
            <div class="flex w-full max-w-3xl items-center justify-end gap-2">
                <form method="GET" action="{{ route('pos.purchases.index') }}" class="flex w-full max-w-md items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by ID, vendor, invoice, product, HSN/SAC..."
                        class="pos-input py-2.5"
                    >
                    <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Search</button>
                    @if (request('search'))
                        <a href="{{ route('pos.purchases.index') }}" class="pos-btn-ghost py-2.5">Clear</a>
                    @endif
                </form>
                <a href="{{ route('pos.purchases.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create Purchase</a>
            </div>
        </div>
        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">ID</th>
                            <th class="px-4 py-3 font-semibold">Vendor</th>
                            <th class="px-4 py-3 font-semibold">Invoice No</th>
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">HSN/SAC</th>
                            <th class="px-4 py-3 font-semibold">Price</th>
                            <th class="px-4 py-3 font-semibold">Qty</th>
                            <th class="px-4 py-3 font-semibold">Total Amount</th>
                            <th class="px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td class="px-4 py-3 font-medium text-sky-700">P{{ str_pad((string) $purchase->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3">{{ $purchase->vendor?->name ?: $purchase->vendor_name }}</td>
                                <td class="px-4 py-3">{{ $purchase->invoice_no }}</td>
                                <td class="px-4 py-3">{{ $purchase->invoice_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $purchase->product_name }}</td>
                                <td class="px-4 py-3">{{ $purchase->hsn_sac ?: '-' }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $purchase->price, 2) }}</td>
                                <td class="px-4 py-3">{{ $purchase->qty }}</td>
                                <td class="px-4 py-3 font-semibold text-sky-700">Rs {{ number_format((float) $purchase->total_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('pos.purchases.edit', $purchase) }}" class="rounded-md bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 ring-1 ring-sky-100 hover:bg-sky-100">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('pos.purchases.destroy', $purchase) }}" onsubmit="return confirm('Delete this purchase?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 ring-1 ring-rose-100 hover:bg-rose-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-slate-500">No purchases found.</td>
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
