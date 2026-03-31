<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalInvoiceAmount = (float) Invoice::sum('total_amount');
        $totalPaymentReceived = (float) Payment::sum('amount');

        return view('dashboard', compact('totalInvoiceAmount', 'totalPaymentReceived'));
    }
}
