<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CompanyProfile;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $customerId = $request->string('customer_id')->toString();
        $status = $request->string('status')->toString();
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();
        $view = $request->string('view')->toString();

        $filters = [
            'customer_id' => $customerId,
            'status' => $status,
            'from' => $from,
            'to' => $to,
        ];

        $invoicesQuery = Invoice::query()->where($this->invoiceFilter($filters));
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

    public function exportCsv(Request $request): StreamedResponse
    {
        $viewMode = $request->string('view')->toString() === 'hsn' ? 'hsn' : 'list';
        $filters = [
            'customer_id' => $request->string('customer_id')->toString(),
            'status' => $request->string('status')->toString(),
            'from' => $request->string('from')->toString(),
            'to' => $request->string('to')->toString(),
        ];

        $invoiceIds = Invoice::query()
            ->where($this->invoiceFilter($filters))
            ->pluck('id');

        $fileName = 'gst-report-' . $viewMode . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($invoiceIds, $viewMode) {
            $handle = fopen('php://output', 'w');

            if ($viewMode === 'hsn') {
                fputcsv($handle, ['HSN/SAC', 'Taxable Value', 'CGST', 'SGST', 'IGST', 'Total Tax']);

                $rows = InvoiceItem::query()
                    ->selectRaw('hsn_sac, SUM(amount) as taxable')
                    ->whereIn('invoice_id', $invoiceIds)
                    ->groupBy('hsn_sac')
                    ->orderBy('hsn_sac')
                    ->get();

                foreach ($rows as $row) {
                    $taxable = (float) $row->taxable;
                    $cgst = $taxable * 0.09;
                    $sgst = $taxable * 0.09;
                    $igst = 0.0;
                    $totalTax = $taxable * 0.18;

                    fputcsv($handle, [
                        $row->hsn_sac ?: '-',
                        number_format($taxable, 2, '.', ''),
                        number_format($cgst, 2, '.', ''),
                        number_format($sgst, 2, '.', ''),
                        number_format($igst, 2, '.', ''),
                        number_format($totalTax, 2, '.', ''),
                    ]);
                }
            } else {
                fputcsv($handle, ['Date', 'Invoice #', 'Customer', 'HSN/SAC', 'Description', 'Rate', 'Amount', 'CGST', 'SGST', 'IGST', 'Total']);

                $rows = InvoiceItem::query()
                    ->with(['invoice.customer'])
                    ->whereIn('invoice_id', $invoiceIds)
                    ->orderByDesc('id')
                    ->get();

                foreach ($rows as $row) {
                    $gstType = $row->invoice?->gst_type;
                    $amount = (float) $row->amount;
                    $cgst = $gstType === 'same' ? $amount * 0.09 : 0.0;
                    $sgst = $gstType === 'same' ? $amount * 0.09 : 0.0;
                    $igst = $gstType === 'other' ? $amount * 0.18 : 0.0;
                    $total = $amount + $cgst + $sgst + $igst;

                    fputcsv($handle, [
                        optional($row->invoice?->invoice_date)->format('d-m-Y'),
                        $row->invoice?->invoice_no,
                        $row->invoice?->customer?->name ?: '-',
                        $row->hsn_sac ?: '-',
                        $row->description,
                        number_format((float) $row->rate, 2, '.', ''),
                        number_format($amount, 2, '.', ''),
                        number_format($cgst, 2, '.', ''),
                        number_format($sgst, 2, '.', ''),
                        number_format($igst, 2, '.', ''),
                        number_format($total, 2, '.', ''),
                    ]);
                }
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    private function invoiceFilter(array $filters): \Closure
    {
        return function (Builder $query) use ($filters): void {
            $customerId = $filters['customer_id'] ?? '';
            $status = $filters['status'] ?? '';
            $from = $filters['from'] ?? '';
            $to = $filters['to'] ?? '';

            $query->where('gst_type', '!=', 'none')
                ->when($customerId !== '', fn (Builder $q) => $q->where('customer_id', $customerId))
                ->when($status !== '', fn (Builder $q) => $q->where('status', $status))
                ->when($from !== '', fn (Builder $q) => $q->whereDate('invoice_date', '>=', $from))
                ->when($to !== '', fn (Builder $q) => $q->whereDate('invoice_date', '<=', $to));
        };
    }
}
