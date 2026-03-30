@extends('layouts.pos-app')

@section('title', $pageTitle . ' — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">{{ $pageTitle }}</h1>
            <a href="{{ route('pos.invoices.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create Invoice</a>
        </div>
        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('pos.invoices.index') }}" class="pos-btn-ghost {{ ($scope ?? 'all') === 'all' ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-100' : '' }}">All Invoices</a>
            <a href="{{ route('pos.invoices.index', ['scope' => 'gst']) }}" class="pos-btn-ghost {{ ($scope ?? 'all') === 'gst' ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-100' : '' }}">GST Invoices</a>
        </div>

        <form method="GET" action="{{ route('pos.invoices.index') }}" class="mt-4 grid gap-3 rounded-lg border border-sky-100 bg-white p-4 md:grid-cols-5">
            <input type="hidden" name="scope" value="{{ $scope ?? 'all' }}">
            <div>
                <label class="pos-label" for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" class="pos-input">
                    <option value="">All Customers</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                            {{ $customer->customer_code }} - {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="pos-label" for="status">Status</label>
                <select id="status" name="status" class="pos-input">
                    <option value="">All</option>
                    <option value="unpaid" @selected(request('status') === 'unpaid')>Unpaid</option>
                    <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="pos-label" for="from">From</label>
                <input id="from" name="from" type="date" value="{{ request('from') }}" class="pos-input">
            </div>
            <div>
                <label class="pos-label" for="to">To</label>
                <input id="to" name="to" type="date" value="{{ request('to') }}" class="pos-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Filter</button>
                <a href="{{ route('pos.invoices.index', ['scope' => $scope ?? 'all']) }}" class="pos-btn-ghost py-2.5">Clear</a>
            </div>
        </form>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Invoice No</th>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Due Date</th>
                            <th class="px-4 py-3 font-semibold">GST Type</th>
                            <th class="px-4 py-3 font-semibold">Subtotal</th>
                            <th class="px-4 py-3 font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td class="px-4 py-3 font-medium text-sky-700">{{ $invoice->invoice_no }}</td>
                                <td class="px-4 py-3">{{ $invoice->customer?->name ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $invoice->due_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 uppercase">{{ $invoice->gst_type }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $invoice->subtotal, 2) }}</td>
                                <td class="px-4 py-3 font-semibold">Rs {{ number_format((float) $invoice->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $invoices->links() }}
        </div>
    </section>
@endsection
