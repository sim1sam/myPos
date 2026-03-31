@extends('layouts.pos-app')

@section('title', 'Invoice & Payment Summary — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Invoice & Payment Summary</h1>
            <a href="{{ route('pos.customers') }}" class="pos-btn-ghost">Back to Customers</a>
        </div>

        <form method="GET" action="{{ route('pos.customers.summary') }}" class="mt-4 grid gap-3 rounded-lg border border-sky-100 bg-white p-4 md:grid-cols-4">
            <div>
                <label class="pos-label" for="customer_search">Customer Name Search</label>
                <input id="customer_search" name="customer_search" type="text" value="{{ request('customer_search') }}" class="pos-input" placeholder="Type customer name/code">
            </div>
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
            <div class="flex items-end gap-2">
                <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Filter</button>
                <a href="{{ route('pos.customers.summary') }}" class="pos-btn-ghost py-2.5">Reset</a>
            </div>
        </form>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Total Invoice (Rs)</th>
                            <th class="px-4 py-3 font-semibold">Total Payment (Rs)</th>
                            <th class="px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($rows as $row)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $row->customer_code }} - {{ $row->name }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $row->total_invoice, 2) }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $row->total_payment, 2) }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('pos.customers.summary-details', $row->id) }}" class="pos-btn-ghost py-1.5 text-xs">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">No customer summary found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $rows->links() }}
        </div>
    </section>
@endsection
