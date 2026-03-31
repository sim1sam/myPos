<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\PaymentMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const COMPANY_PROFILE_DEFAULTS = [
        'company_name' => 'Wise Dynamic Private Limited',
        'company_email' => 'hello@wisedynamic.in',
        'mobile_number' => '6290731704',
        'company_gstin' => '19AADCW5913E1ZA',
        'address' => 'L.P-207/38/2, Sreenagar Paschim Para, Panchasayar',
        'city' => 'Kolkata',
        'pin' => '700094',
        'state' => 'West Bengal',
        'account_holder_name' => 'Wise Dynamic Private Limited',
        'account_number' => '572005000003',
        'bank_name' => 'ICICI Bank',
        'branch' => 'Chak Garia, Kolkata',
        'ifsc_code' => 'ICIC0005720',
        'company_pan' => 'AADCW5913E',
        'declaration' => 'We declare that, this invoice shows the actual price of the goods described and that all particulars are true and correct.',
        'footer_text' => '(This is a computer-generated invoice. No signature is required.)',
    ];

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

    public function companyProfile(): View
    {
        $profile = CompanyProfile::first();

        return view('pos.company-profile', [
            'profile' => $profile,
            'defaults' => self::COMPANY_PROFILE_DEFAULTS,
        ]);
    }

    public function updateCompanyProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
            'qr_code' => ['nullable', 'image', 'max:2048'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'mobile_number' => ['nullable', 'string', 'max:30'],
            'company_gstin' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'pin' => ['nullable', 'string', 'max:20'],
            'state' => ['nullable', 'string', 'max:100'],
            'account_holder_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:150'],
            'branch' => ['nullable', 'string', 'max:150'],
            'ifsc_code' => ['nullable', 'string', 'max:30'],
            'company_pan' => ['nullable', 'string', 'max:30'],
            'declaration' => ['nullable', 'string'],
            'footer_text' => ['nullable', 'string'],
        ]);

        $profile = CompanyProfile::firstOrCreate([], self::COMPANY_PROFILE_DEFAULTS);

        if ($request->hasFile('logo')) {
            if ($profile->logo_path) {
                $oldLogo = public_path($profile->logo_path);
                if (File::exists($oldLogo)) {
                    File::delete($oldLogo);
                }
            }
            $directory = public_path('uploads/company-profile');
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            $logoName = 'logo_' . time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($directory, $logoName);
            $data['logo_path'] = 'uploads/company-profile/' . $logoName;
        }

        if ($request->hasFile('qr_code')) {
            if ($profile->qr_code_path) {
                $oldQr = public_path($profile->qr_code_path);
                if (File::exists($oldQr)) {
                    File::delete($oldQr);
                }
            }
            $directory = public_path('uploads/company-profile');
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            $qrName = 'qr_' . time() . '.' . $request->file('qr_code')->getClientOriginalExtension();
            $request->file('qr_code')->move($directory, $qrName);
            $data['qr_code_path'] = 'uploads/company-profile/' . $qrName;
        }

        unset($data['logo'], $data['qr_code']);

        $profile->update($data);

        return redirect()->route('pos.settings.company-profile')->with('success', 'Company profile updated successfully.');
    }
}
