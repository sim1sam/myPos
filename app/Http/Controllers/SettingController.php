<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('pos.settings');
    }

    public function paymentModes(): View
    {
        $paymentModes = PaymentMode::orderBy('name')->paginate(15);

        return view('pos.payment-modes-index', compact('paymentModes'));
    }

    public function createPaymentMode(): View
    {
        return view('pos.payment-modes-create');
    }

    public function storePaymentMode(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:payment_modes,name'],
        ]);

        PaymentMode::create($data);

        return redirect()->route('pos.settings.payment-modes.index')->with('success', 'Payment mode added successfully.');
    }
}
