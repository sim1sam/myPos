@extends('layouts.pos-app')

@section('title', $pageTitle . ' — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">{{ $pageTitle }}</h1>
            <a href="{{ route($createRoute ?? 'pos.invoices.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">
                {{ ($isFreeInvoice ?? false) ? 'Create Free Invoice' : 'Create Invoice' }}
            </a>
        </div>
        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (!($isFreeInvoice ?? false))
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route($listRoute ?? 'pos.invoices.index') }}" class="pos-btn-ghost {{ ($scope ?? 'all') === 'all' ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-100' : '' }}">All Invoices</a>
                <a href="{{ route($listRoute ?? 'pos.invoices.index', ['scope' => 'gst']) }}" class="pos-btn-ghost {{ ($scope ?? 'all') === 'gst' ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-100' : '' }}">GST Invoices</a>
            </div>
        @endif

        <form method="GET" action="{{ route($listRoute ?? 'pos.invoices.index') }}" class="mt-4 grid gap-3 rounded-lg border border-sky-100 bg-white p-4 md:grid-cols-5">
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
                <a href="{{ route($listRoute ?? 'pos.invoices.index', ['scope' => $scope ?? 'all']) }}" class="pos-btn-ghost py-2.5">Clear</a>
            </div>
        </form>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">SL#</th>
                            <th class="px-4 py-3 font-semibold">Invoice No</th>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Due Date</th>
                            <th class="px-4 py-3 font-semibold">GST Type</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Subtotal</th>
                            <th class="px-4 py-3 font-semibold">Total</th>
                            <th class="px-4 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 font-medium text-sky-700">{{ $invoice->invoice_no }}</td>
                                <td class="px-4 py-3">{{ $invoice->customer?->name ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $invoice->due_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 uppercase">
                                    @if ($invoice->gst_type === 'none')
                                        <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">NON GST</span>
                                    @else
                                        <span class="rounded-md bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">GST</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-md bg-sky-100 px-2 py-1 text-xs font-medium uppercase tracking-wide text-sky-700">{{ $invoice->status }}</span>
                                </td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $invoice->subtotal, 2) }}</td>
                                <td class="px-4 py-3 font-semibold">Rs {{ number_format((float) $invoice->total_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('pos.invoices.show', $invoice) }}" class="pos-btn-ghost py-1.5 text-xs">View</a>
                                        <a href="{{ route('pos.invoices.edit', $invoice) }}" class="pos-btn-ghost py-1.5 text-xs">Edit</a>
                                        <form method="POST" action="{{ route('pos.invoices.destroy', $invoice) }}" onsubmit="return confirm('Delete this invoice?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-slate-500">No invoices found.</td>
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
