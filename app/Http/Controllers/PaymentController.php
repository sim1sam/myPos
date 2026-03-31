<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $customerId = $request->string('customer_id')->toString();
        $paymentModeId = $request->string('payment_mode_id')->toString();
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();

        $customers = Customer::orderBy('name')->get(['id', 'name', 'customer_code']);
        $paymentModes = PaymentMode::orderBy('name')->get(['id', 'name']);

        $payments = Payment::query()
            ->with(['customer:id,name,customer_code', 'paymentMode:id,name'])
            ->when($customerId !== '', fn ($q) => $q->where('customer_id', $customerId))
            ->when($paymentModeId !== '', fn ($q) => $q->where('payment_mode_id', $paymentModeId))
            ->when($from !== '', fn ($q) => $q->whereDate('payment_date', '>=', $from))
            ->when($to !== '', fn ($q) => $q->whereDate('payment_date', '<=', $to))
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('pos.payments-index', compact('payments', 'customers', 'paymentModes'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get(['id', 'name', 'customer_code']);
        $paymentModes = PaymentMode::orderBy('name')->get(['id', 'name']);

        return view('pos.payments-create', compact('customers', 'paymentModes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_mode_id' => ['required', 'exists:payment_modes,id'],
            'payment_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        Payment::create($data);

        return redirect()->route('pos.inventory')->with('success', 'Payment created successfully.');
    }
}
