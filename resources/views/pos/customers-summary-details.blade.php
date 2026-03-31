@extends('layouts.pos-app')

@section('title', 'Customer Summary Details — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Customer Summary Details</h1>
            <a href="{{ route('pos.customers.summary', ['customer_id' => $customer->id]) }}" class="pos-btn-ghost">Back</a>
        </div>

        <div class="mt-4 rounded-lg border border-sky-100 bg-white p-4">
            <p class="text-sm text-slate-500">Customer</p>
            <p class="mt-1 text-xl font-semibold text-slate-800">{{ $customer->customer_code }} - {{ $customer->name }}</p>
            <div class="mt-3 grid gap-3 md:grid-cols-2">
                <div class="rounded-md bg-sky-50 px-3 py-2">
                    <p class="text-xs text-slate-500">Total Invoice</p>
                    <p class="text-lg font-semibold text-sky-700">Rs {{ number_format($totalInvoice, 2) }}</p>
                </div>
                <div class="rounded-md bg-emerald-50 px-3 py-2">
                    <p class="text-xs text-slate-500">Total Payment</p>
                    <p class="text-lg font-semibold text-emerald-700">Rs {{ number_format($totalPayment, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-2">
            <div class="pos-dashboard-card p-0">
                <div class="border-b border-slate-100 px-4 py-3">
                    <h2 class="text-lg font-semibold text-slate-800">Invoices</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-sky-50 text-left text-slate-700">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Invoice #</th>
                                <th class="px-4 py-3 font-semibold">Date</th>
                                <th class="px-4 py-3 font-semibold">Amount</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="px-4 py-3">{{ $invoice->invoice_no }}</td>
                                    <td class="px-4 py-3">{{ optional($invoice->invoice_date)->format('d-m-Y') }}</td>
                                    <td class="px-4 py-3">Rs {{ number_format((float) $invoice->total_amount, 2) }}</td>
                                    <td class="px-4 py-3 uppercase">{{ $invoice->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">No invoices found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pos-dashboard-card p-0">
                <div class="border-b border-slate-100 px-4 py-3">
                    <h2 class="text-lg font-semibold text-slate-800">Payments</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-sky-50 text-left text-slate-700">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Date</th>
                                <th class="px-4 py-3 font-semibold">Mode</th>
                                <th class="px-4 py-3 font-semibold">Amount</th>
                                <th class="px-4 py-3 font-semibold">Note</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($payments as $payment)
                                <tr>
                                    <td class="px-4 py-3">{{ optional($payment->payment_date)->format('d-m-Y') }}</td>
                                    <td class="px-4 py-3">{{ $payment->paymentMode?->name ?: '-' }}</td>
                                    <td class="px-4 py-3">Rs {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ $payment->note ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">No payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
