<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CompanyProfile;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $customerId = $request->string('customer_id')->toString();
        $status = $request->string('status')->toString();
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();
        $view = $request->string('view')->toString();

        $invoiceFilter = function (Builder $query) use ($customerId, $status, $from, $to): void {
            $query->where('gst_type', '!=', 'none')
                ->when($customerId !== '', fn (Builder $q) => $q->where('customer_id', $customerId))
                ->when($status !== '', fn (Builder $q) => $q->where('status', $status))
                ->when($from !== '', fn (Builder $q) => $q->whereDate('invoice_date', '>=', $from))
                ->when($to !== '', fn (Builder $q) => $q->whereDate('invoice_date', '<=', $to));
        };

        $invoicesQuery = Invoice::query()->where($invoiceFilter);
        $invoiceIds = $invoicesQuery->pluck('id');

        $summary = [
            'total_gst_invoices' => (int) $invoicesQuery->count(),
            'total_amount' => (float) $invoicesQuery->sum('total_amount'),
            'total_cgst_sgst' => (float) $invoicesQuery->sum('cgst') + (float) $invoicesQuery->sum('sgst'),
            'total_igst' => (float) $invoicesQuery->sum('igst'),
        ];

        $itemRows = InvoiceItem::query()
            ->with(['invoice.customer'])
            ->whereIn('invoice_id', $invoiceIds)
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $hsnSummary = InvoiceItem::query()
            ->selectRaw('hsn_sac, SUM(amount) as taxable')
            ->whereIn('invoice_id', $invoiceIds)
            ->groupBy('hsn_sac')
            ->orderBy('hsn_sac')
            ->get()
            ->map(function ($row) {
                $taxable = (float) $row->taxable;
                return [
                    'hsn_sac' => $row->hsn_sac ?: '-',
                    'taxable' => $taxable,
                    'cgst' => $taxable * 0.09,
                    'sgst' => $taxable * 0.09,
                    'igst' => 0.0,
                    'total_tax' => $taxable * 0.18,
                ];
            });

        $customers = Customer::orderBy('name')->get(['id', 'name', 'customer_code']);
        $selectedCustomer = $customerId !== '' ? Customer::find($customerId, ['id', 'name', 'customer_code']) : null;
        $companyProfile = CompanyProfile::first();

        return view('pos.reports', [
            'customers' => $customers,
            'selectedCustomer' => $selectedCustomer,
            'itemRows' => $itemRows,
            'summary' => $summary,
            'hsnSummary' => $hsnSummary,
            'viewMode' => $view === 'hsn' ? 'hsn' : 'list',
            'companyProfile' => $companyProfile,
        ]);
    }
}
