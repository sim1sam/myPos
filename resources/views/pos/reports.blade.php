@extends('layouts.pos-app')

@section('title', 'GST Invoice Report — ' . config('app.name'))

@section('page-content')
    @php
        $companyName = strtoupper($companyProfile?->company_name ?: config('app.name'));
    @endphp
    <style>
        @media print {
            header,
            footer,
            main > div:first-child {
                display: none !important;
            }
            body,
            main {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            section {
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .report-no-print {
                display: none !important;
            }
            .report-print-wrap {
                width: 194mm;
                margin: 10mm auto 0;
            }
            .report-print-header {
                display: flex !important;
            }
        }
        .report-print-header {
            display: none;
        }
    </style>
    <section class="mx-auto max-w-screen-2xl">
        <div class="report-print-wrap">
        <div class="report-print-header mb-3 items-center justify-between border-b border-slate-300 pb-3">
            <div class="flex items-center gap-3">
                @if (!empty($companyProfile?->logo_path))
                    <img src="{{ asset($companyProfile->logo_path) }}" alt="Company Logo" class="h-10 w-auto object-contain">
                @endif
                <div>
                    <p class="text-lg font-bold text-slate-800">{{ $companyName }}</p>
                    <p class="text-xs text-slate-500">GST Invoice Report</p>
                </div>
            </div>
            <div class="text-right text-xs text-slate-600">
                <p>Date: {{ now()->format('d-m-Y') }}</p>
                <p>Customer: {{ $selectedCustomer?->name ?: 'All Customers' }}</p>
            </div>
        </div>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">GST Invoice Report</h1>
        <p class="mt-1 text-sm text-slate-500">Customer: {{ $selectedCustomer?->name ?: 'All Customers' }}</p>

        <form method="GET" action="{{ route('pos.reports') }}" class="report-no-print mt-5 grid gap-3 rounded-lg border border-sky-100 bg-white p-4 md:grid-cols-6">
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
                <label class="pos-label" for="from">From Date</label>
                <input id="from" name="from" type="date" value="{{ request('from') }}" class="pos-input">
            </div>
            <div>
                <label class="pos-label" for="to">To Date</label>
                <input id="to" name="to" type="date" value="{{ request('to') }}" class="pos-input">
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Generate Report</button>
            </div>
        </form>

        <div class="report-no-print mt-3 flex flex-wrap gap-2">
            <a href="{{ route('pos.reports', array_filter(array_merge(request()->query(), ['view' => 'hsn']))) }}"
               class="pos-btn-ghost {{ $viewMode === 'hsn' ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-100' : '' }}">
                HSN/SAC Summary
            </a>
            <a href="{{ route('pos.reports', array_filter(array_merge(request()->query(), ['view' => 'list']))) }}"
               class="pos-btn-ghost {{ $viewMode === 'list' ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-100' : '' }}">
                Detailed List
            </a>
            <a href="{{ route('pos.reports') }}" class="pos-btn-ghost">Reset</a>
            <button type="button" onclick="window.print()" class="pos-btn-primary w-auto! px-5 py-2">Print Report</button>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg border border-sky-100 bg-white px-4 py-4 text-sky-700 shadow-sm">
                <p class="text-sm">Total GST Invoices</p>
                <p class="mt-2 text-3xl font-bold">{{ $summary['total_gst_invoices'] }}</p>
            </div>
            <div class="rounded-lg border border-sky-100 bg-white px-4 py-4 text-sky-700 shadow-sm">
                <p class="text-sm">Total Amount</p>
                <p class="mt-2 text-3xl font-bold">Rs {{ number_format($summary['total_amount'], 2) }}</p>
            </div>
            <div class="rounded-lg border border-sky-100 bg-white px-4 py-4 text-sky-700 shadow-sm">
                <p class="text-sm">Total CGST+SGST</p>
                <p class="mt-2 text-3xl font-bold">Rs {{ number_format($summary['total_cgst_sgst'], 2) }}</p>
            </div>
            <div class="rounded-lg border border-sky-100 bg-white px-4 py-4 text-sky-700 shadow-sm">
                <p class="text-sm">Total IGST</p>
                <p class="mt-2 text-3xl font-bold">Rs {{ number_format($summary['total_igst'], 2) }}</p>
            </div>
        </div>

        @if ($viewMode === 'hsn')
            <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-3 py-2 font-semibold">HSN/SAC</th>
                            <th class="px-3 py-2 font-semibold text-right">Taxable Value</th>
                            <th class="px-3 py-2 font-semibold text-right">CGST</th>
                            <th class="px-3 py-2 font-semibold text-right">SGST</th>
                            <th class="px-3 py-2 font-semibold text-right">IGST</th>
                            <th class="px-3 py-2 font-semibold text-right">Total Tax</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($hsnSummary as $row)
                            <tr>
                                <td class="px-3 py-2">{{ $row['hsn_sac'] }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($row['taxable'], 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($row['cgst'], 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($row['sgst'], 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($row['igst'], 2) }}</td>
                                <td class="px-3 py-2 text-right font-semibold">{{ number_format($row['total_tax'], 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-3 py-6 text-center text-slate-500">No HSN/SAC summary data found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-3 py-2 font-semibold">Date</th>
                            <th class="px-3 py-2 font-semibold">Invoice #</th>
                            <th class="px-3 py-2 font-semibold">Customer</th>
                            <th class="px-3 py-2 font-semibold">HSN/SAC</th>
                            <th class="px-3 py-2 font-semibold">Description</th>
                            <th class="px-3 py-2 font-semibold text-right">Rate</th>
                            <th class="px-3 py-2 font-semibold text-right">Amount</th>
                            <th class="px-3 py-2 font-semibold text-right">CGST</th>
                            <th class="px-3 py-2 font-semibold text-right">SGST</th>
                            <th class="px-3 py-2 font-semibold text-right">IGST</th>
                            <th class="px-3 py-2 font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($itemRows as $row)
                            @php
                                $gstType = $row->invoice?->gst_type;
                                $cgst = $gstType === 'same' ? (float) $row->amount * 0.09 : 0;
                                $sgst = $gstType === 'same' ? (float) $row->amount * 0.09 : 0;
                                $igst = $gstType === 'other' ? (float) $row->amount * 0.18 : 0;
                                $total = (float) $row->amount + $cgst + $sgst + $igst;
                            @endphp
                            <tr>
                                <td class="px-3 py-2">{{ optional($row->invoice?->invoice_date)->format('d-m-Y') }}</td>
                                <td class="px-3 py-2">{{ $row->invoice?->invoice_no }}</td>
                                <td class="px-3 py-2">{{ $row->invoice?->customer?->name ?: '-' }}</td>
                                <td class="px-3 py-2">{{ $row->hsn_sac ?: '-' }}</td>
                                <td class="px-3 py-2">{{ $row->description }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format((float) $row->rate, 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format((float) $row->amount, 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($cgst, 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($sgst, 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($igst, 2) }}</td>
                                <td class="px-3 py-2 text-right font-semibold">{{ number_format($total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-3 py-8 text-center text-slate-500">No GST invoice rows found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pos-pagination report-no-print mt-4">
                {{ $itemRows->links() }}
            </div>
        @endif
        </div>
    </section>
@endsection
