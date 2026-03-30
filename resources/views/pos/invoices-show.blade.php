@extends('layouts.pos-app')

@section('title', 'Invoice Details — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-screen-2xl">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-slate-800">Invoice {{ $invoice->invoice_no }}</h1>
            <a href="{{ route('pos.invoices.index') }}" class="pos-btn-ghost">Back</a>
        </div>

        <div class="pos-dashboard-card">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Customer</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $invoice->customer?->name ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Invoice Date</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $invoice->invoice_date?->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Due Date</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $invoice->due_date?->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">GST Type</p>
                    <p class="mt-1 font-semibold uppercase text-slate-800">{{ $invoice->gst_type }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Status</p>
                    <p class="mt-1 font-semibold uppercase text-slate-800">{{ $invoice->status }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Amount</p>
                    <p class="mt-1 font-semibold text-sky-700">Rs {{ number_format((float) $invoice->total_amount, 2) }}</p>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">SL#</th>
                            <th class="px-4 py-3 font-semibold">Description</th>
                            <th class="px-4 py-3 font-semibold">HSN/SAC</th>
                            <th class="px-4 py-3 font-semibold">Rate</th>
                            <th class="px-4 py-3 font-semibold">Qty</th>
                            <th class="px-4 py-3 font-semibold">Discount</th>
                            <th class="px-4 py-3 font-semibold">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($invoice->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $item->description }}</td>
                                <td class="px-4 py-3">{{ $item->hsn_sac ?: '-' }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $item->rate, 2) }}</td>
                                <td class="px-4 py-3">{{ $item->qty }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $item->discount, 2) }}</td>
                                <td class="px-4 py-3 font-semibold">Rs {{ number_format((float) $item->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-slate-500">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
