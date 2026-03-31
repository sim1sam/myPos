@extends('layouts.pos-app')

@section('title', 'Invoice Details — ' . config('app.name'))

@section('page-content')
    @php
        $companyName = strtoupper($companyProfile?->company_name ?: config('app.name'));
        $itemGroups = $invoice->items->groupBy(fn ($item) => $item->hsn_sac ?: 'N/A');
    @endphp

    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        .a4-wrap {
            display: flex;
            justify-content: center;
        }

        .invoice-sheet {
            width: 210mm;
            min-height: 297mm;
            box-sizing: border-box;
            margin-top: 12px;
        }

        @media print {
            header,
            footer,
            main > div:first-child,
            .invoice-actions {
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
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .a4-wrap {
                display: block !important;
            }
            .invoice-sheet {
                box-shadow: none !important;
                border: 0 !important;
                margin: 8mm auto 0 !important;
                width: 194mm !important;
                min-height: auto !important;
                max-width: 194mm !important;
                padding: 0 !important;
            }
            .invoice-sheet table {
                width: 100% !important;
                table-layout: fixed;
            }
        }
    </style>

    <section class="mx-auto max-w-screen-2xl">
        <div class="invoice-actions mb-4 flex flex-wrap items-center justify-end gap-2">
            <button type="button" onclick="window.print()" class="rounded-md bg-rose-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-rose-700">
                Print Invoice
            </button>
            <button type="button" onclick="window.print()" class="rounded-md bg-slate-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-700">
                Download PDF
            </button>
        </div>

        <div class="a4-wrap">
        <div class="invoice-sheet rounded-lg border border-slate-300 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-300 pb-4">
                <div>
                    @if (!empty($companyProfile?->logo_path))
                        <img src="{{ asset($companyProfile->logo_path) }}" alt="Company Logo" class="mb-2 h-12 w-auto object-contain">
                    @else
                        <h1 class="text-3xl font-black tracking-wide text-sky-800">{{ $companyName }}</h1>
                    @endif
                    <p class="mt-1 text-xs text-slate-500">Tax Invoice</p>
                </div>
                <div class="text-right text-xs text-slate-700">
                    <p class="font-semibold">Original for Recipient</p>
                    <p class="mt-1"><span class="font-semibold">Invoice No:</span> {{ $invoice->invoice_no }}</p>
                    <p><span class="font-semibold">Billing Date:</span> {{ $invoice->invoice_date?->format('d M Y') }}</p>
                    <p><span class="font-semibold">Due Date:</span> {{ $invoice->due_date?->format('d M Y') }}</p>
                </div>
            </div>

            <div class="grid gap-4 border-b border-slate-300 py-4 md:grid-cols-2">
                <div class="text-xs text-slate-700">
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-slate-500">Billed By</p>
                    <p class="font-semibold text-slate-900">{{ $companyName }}</p>
                    <p>{{ $companyProfile?->address ?: config('app.url') }}</p>
                    <p>{{ $companyProfile?->city ?: '' }}{{ $companyProfile?->city && $companyProfile?->pin ? ', ' : '' }}{{ $companyProfile?->pin ?: '' }} {{ $companyProfile?->state ?: '' }}</p>
                    <p>GSTIN: {{ $companyProfile?->company_gstin ?: '-' }}</p>
                    <p>Mobile: {{ $companyProfile?->mobile_number ?: '-' }}</p>
                </div>
                <div class="text-xs text-slate-700">
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-slate-500">Billed To</p>
                    <p class="font-semibold text-slate-900">{{ $invoice->customer?->name ?: 'Walk-in Customer' }}</p>
                    <p>{{ $invoice->customer?->address ?: '-' }}</p>
                    <p>{{ $invoice->customer?->city ?: '-' }}, {{ $invoice->customer?->state ?: '-' }} {{ $invoice->customer?->pin_code ?: '' }}</p>
                    <p>GSTIN: {{ $invoice->customer?->gstin ?: '-' }}</p>
                    <p>Mobile: {{ $invoice->customer?->mobile ?: '-' }}</p>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full border border-slate-300 text-xs">
                    <thead class="bg-slate-100 text-left text-slate-700">
                        <tr>
                            <th class="border border-slate-300 px-2 py-2 font-semibold">SL#</th>
                            <th class="border border-slate-300 px-2 py-2 font-semibold">Description of Goods</th>
                            <th class="border border-slate-300 px-2 py-2 font-semibold">HSN/SAC</th>
                            <th class="border border-slate-300 px-2 py-2 font-semibold text-right">Rate</th>
                            <th class="border border-slate-300 px-2 py-2 font-semibold text-right">Qty</th>
                            <th class="border border-slate-300 px-2 py-2 font-semibold text-right">Disc.</th>
                            <th class="border border-slate-300 px-2 py-2 font-semibold text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoice->items as $item)
                            <tr>
                                <td class="border border-slate-300 px-2 py-2">{{ $loop->iteration }}</td>
                                <td class="border border-slate-300 px-2 py-2">{{ $item->description }}</td>
                                <td class="border border-slate-300 px-2 py-2">{{ $item->hsn_sac ?: '-' }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format((float) $item->rate, 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ $item->qty }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format((float) $item->discount, 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right font-semibold">{{ number_format((float) $item->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="border border-slate-300 px-2 py-6 text-center text-slate-500">No items found.</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="6" class="border border-slate-300 px-2 py-2 text-right font-semibold">Subtotal</td>
                            <td class="border border-slate-300 px-2 py-2 text-right font-semibold">{{ number_format((float) $invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border border-slate-300 px-2 py-2 text-right">SGST</td>
                            <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format((float) $invoice->sgst, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border border-slate-300 px-2 py-2 text-right">CGST</td>
                            <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format((float) $invoice->cgst, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border border-slate-300 px-2 py-2 text-right">IGST</td>
                            <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format((float) $invoice->igst, 2) }}</td>
                        </tr>
                        <tr class="bg-slate-100">
                            <td colspan="6" class="border border-slate-300 px-2 py-2 text-right font-bold">Total Amount</td>
                            <td class="border border-slate-300 px-2 py-2 text-right font-bold">{{ number_format((float) $invoice->total_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-right text-xs text-amber-700">
                Please Pay By: {{ $invoice->due_date?->format('d-M-Y') }}
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full border border-slate-300 text-xs">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="border border-slate-300 px-2 py-2 text-left font-semibold">HSN/SAC</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">Taxable Value</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">SGST</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">CGST</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">IGST</th>
                            <th class="border border-slate-300 px-2 py-2 text-right font-semibold">Total Tax</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemGroups as $hsn => $groupItems)
                            @php
                                $taxable = (float) $groupItems->sum('amount');
                                $sgst = $invoice->gst_type === 'same' ? $taxable * 0.09 : 0;
                                $cgst = $invoice->gst_type === 'same' ? $taxable * 0.09 : 0;
                                $igst = $invoice->gst_type === 'other' ? $taxable * 0.18 : 0;
                            @endphp
                            <tr>
                                <td class="border border-slate-300 px-2 py-2">{{ $hsn }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($taxable, 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($sgst, 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($cgst, 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($igst, 2) }}</td>
                                <td class="border border-slate-300 px-2 py-2 text-right">{{ number_format($sgst + $cgst + $igst, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5 grid gap-4 border-t border-slate-300 pt-4 md:grid-cols-2">
                <div class="text-[11px] text-slate-700">
                    <p class="font-semibold">Payment Details</p>
                    <p>A/C Name: {{ $companyProfile?->account_holder_name ?: $companyName }}</p>
                    <p>A/C Number: {{ $companyProfile?->account_number ?: '-' }}</p>
                    <p>IFSC Code: {{ $companyProfile?->ifsc_code ?: '-' }}</p>
                    <p>Branch: {{ $companyProfile?->branch ?: '-' }}</p>
                    <p class="mt-2">{{ $companyProfile?->address ?: '-' }}</p>
                </div>
                <div class="text-right text-[11px] text-slate-700">
                    <p class="font-semibold">Declaration</p>
                    <p>{{ $companyProfile?->declaration ?: 'We declare that this invoice shows the actual price of the goods described and all particulars are true and correct.' }}</p>
                    <p class="mt-6 font-semibold">{{ $companyName }}</p>
                    <p class="mt-4">Authorized Signatory</p>
                </div>
            </div>
            @if (!empty($companyProfile?->footer_text))
                <div class="mt-3 border-t border-slate-200 pt-2 text-center text-[10px] text-slate-500">
                    {{ $companyProfile->footer_text }}
                </div>
            @endif
        </div>
        </div>
    </section>
@endsection
