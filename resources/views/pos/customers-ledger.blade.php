@extends('layouts.pos-app')

@section('title', 'Customer Ledger — ' . config('app.name'))

@section('page-content')
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        .ledger-sheet {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        @media print {
            header,
            footer,
            main > div:first-child {
                display: none !important;
            }

            body,
            main {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .ledger-no-print {
                display: none !important;
            }

            section {
                max-width: none !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .ledger-sheet {
                width: 194mm !important;
                max-width: 194mm !important;
                margin: 0 auto !important;
                border: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>

    <section class="mx-auto max-w-5xl">
        <h1 class="ledger-no-print text-4xl font-semibold tracking-tight text-slate-800">Customer Ledger</h1>

        <form method="GET" action="{{ route('pos.customers.ledger') }}" class="ledger-no-print mt-5 grid gap-3 rounded-lg border border-sky-100 bg-white p-4 md:grid-cols-6">
            <div class="md:col-span-2">
                <label class="pos-label" for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" class="pos-input">
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="pos-label" for="gst_filter">GST Filter</label>
                <select id="gst_filter" name="gst_filter" class="pos-input">
                    <option value="all" @selected($gstFilter === 'all')>All Invoices</option>
                    <option value="gst" @selected($gstFilter === 'gst')>GST Invoices</option>
                    <option value="non_gst" @selected($gstFilter === 'non_gst')>Non GST Invoices</option>
                </select>
            </div>
            <div>
                <label class="pos-label" for="from">From Date</label>
                <input id="from" name="from" type="date" value="{{ $from }}" class="pos-input">
            </div>
            <div>
                <label class="pos-label" for="to">To Date</label>
                <input id="to" name="to" type="date" value="{{ $to }}" class="pos-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Search</button>
                <a href="{{ route('pos.customers.ledger') }}" class="pos-btn-ghost py-2.5">Reset</a>
            </div>
        </form>

        <div class="ledger-no-print mt-3 flex justify-center">
            <button type="button" onclick="window.print()" class="rounded-md bg-rose-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-700">
                Print Ledger
            </button>
        </div>

        <div class="ledger-sheet mt-4 rounded-lg border border-slate-300 bg-white p-5 shadow-sm">
            <div class="text-center">
                <h2 class="text-3xl font-black tracking-wide text-sky-800">{{ strtoupper(config('app.name')) }}</h2>
                <p class="text-xs text-slate-600">{{ config('app.url') }}</p>
                <p class="text-xs text-slate-600">Phone: - | GSTIN: -</p>
            </div>

            <div class="mt-4 grid gap-3 text-xs text-slate-700 md:grid-cols-3">
                <div>
                    <p><span class="font-semibold">Customer:</span> {{ $selectedCustomer?->name ?: '-' }}</p>
                    <p><span class="font-semibold">GSTIN:</span> {{ $selectedCustomer?->gstin ?: '-' }}</p>
                    <p><span class="font-semibold">Phone:</span> {{ $selectedCustomer?->mobile ?: '-' }}</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold text-slate-800">Customer Ledger Statement</p>
                </div>
                <div class="text-right">
                    <p><span class="font-semibold">Report Date:</span> {{ now()->format('d-M-Y') }}</p>
                    <p><span class="font-semibold">Period:</span> {{ \Illuminate\Support\Carbon::parse($from)->format('d-M-Y') }} to {{ \Illuminate\Support\Carbon::parse($to)->format('d-M-Y') }}</p>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full border border-slate-300 text-xs">
                    <thead class="bg-slate-100 text-left text-slate-700">
                        <tr>
                            <th class="border border-slate-300 px-2 py-2 font-semibold">Date</th>
                            <th class="border border-slate-300 px-2 py-2 font-semibold">Particulars</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">Debit (Rs)</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">Credit (Rs)</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">Balance (Rs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-slate-300 px-2 py-2">{{ \Illuminate\Support\Carbon::parse($from)->format('d-m-Y') }}</td>
                            <td class="border border-slate-300 px-2 py-2">Opening Balance</td>
                            <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($openingBalance > 0 ? $openingBalance : 0, 2) }}</td>
                            <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($openingBalance < 0 ? abs($openingBalance) : 0, 2) }}</td>
                            <td class="border border-slate-300 px-2 py-2 text-right font-semibold">
                                {{ number_format(abs($openingBalance), 2) }} {{ $openingBalance >= 0 ? 'Dr' : 'Cr' }}
                            </td>
                        </tr>

                        @forelse ($entries as $entry)
                            <tr>
                                <td class="border border-slate-300 px-2 py-2">{{ \Illuminate\Support\Carbon::parse($entry['date'])->format('d-m-Y') }}</td>
                                <td class="border border-slate-300 px-2 py-2">{{ $entry['particular'] }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($entry['debit'], 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($entry['credit'], 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right font-semibold">
                                    {{ number_format(abs($entry['balance']), 2) }} {{ $entry['balance'] >= 0 ? 'Dr' : 'Cr' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-slate-300 px-2 py-6 text-center text-slate-500">
                                    Select customer and click Search to view ledger entries.
                                </td>
                            </tr>
                        @endforelse

                        <tr class="bg-slate-100">
                            <td class="border border-slate-300 px-2 py-2 font-semibold" colspan="2">Closing Balance</td>
                            <td class="border border-slate-300 px-2 py-2 text-right font-semibold">{{ number_format($totalDebit, 2) }}</td>
                            <td class="border border-slate-300 px-2 py-2 text-right font-semibold">{{ number_format($totalCredit, 2) }}</td>
                            <td class="border border-slate-300 px-2 py-2 text-right font-semibold">
                                {{ number_format(abs($closingBalance), 2) }} {{ $closingBalance >= 0 ? 'Dr' : 'Cr' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
