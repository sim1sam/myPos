@extends('layouts.pos-app')

@section('title', 'All Payments — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">All Payments</h1>
            <a href="{{ route('pos.inventory') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create Payment</a>
        </div>

        <form method="GET" action="{{ route('pos.payments.index') }}" class="mt-4 grid gap-3 rounded-lg border border-sky-100 bg-white p-4 md:grid-cols-5">
            <div>
                <label class="pos-label" for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" class="pos-input">
                    <option value="">All</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                            {{ $customer->customer_code }} - {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="pos-label" for="payment_mode_id">Payment Mode</label>
                <select id="payment_mode_id" name="payment_mode_id" class="pos-input">
                    <option value="">All</option>
                    @foreach ($paymentModes as $mode)
                        <option value="{{ $mode->id }}" @selected(request('payment_mode_id') == $mode->id)>{{ $mode->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="pos-label" for="from">From Date</label>
                <input id="from" name="from" type="date" value="{{ request('from') }}" class="pos-input">
            </div>
            <div>
                <label class="pos-label" for="to">To Date</label>
                <input id="to" name="to" type="date" value="{{ request('to') }}" class="pos-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Filter</button>
                <a href="{{ route('pos.payments.index') }}" class="pos-btn-ghost py-2.5">Reset</a>
            </div>
        </form>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">SL#</th>
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Payment Mode</th>
                            <th class="px-4 py-3 font-semibold">Amount</th>
                            <th class="px-4 py-3 font-semibold">Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($payments as $payment)
                            <tr>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-3">{{ optional($payment->payment_date)->format('d-m-Y') }}</td>
                                <td class="px-4 py-3">{{ $payment->customer?->customer_code }} - {{ $payment->customer?->name }}</td>
                                <td class="px-4 py-3">{{ $payment->paymentMode?->name }}</td>
                                <td class="px-4 py-3 font-semibold text-sky-700">Rs {{ number_format((float) $payment->amount, 2) }}</td>
                                <td class="px-4 py-3">{{ $payment->note ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $payments->links() }}
        </div>
    </section>
@endsection
