<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\GstRate;
use App\Models\PaymentMode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const ROLE_FUNCTION_OPTIONS = [
        'dashboard' => 'Dashboard',
        'pos' => 'POS',
        'purchases' => 'Purchases',
        'vendors' => 'Vendors',
        'invoices' => 'Invoices',
        'customers' => 'Customers',
        'payments' => 'Payments',
        'reports' => 'Reports',
        'expenses' => 'Expenses',
        'settings' => 'Settings',
    ];
    private const COMPANY_PROFILE_DEFAULTS = [
        'company_name' => 'WISE DYNAMIC PRIVATE LIMITED',
        'company_email' => 'hello@wisedynamic.in',
        'mobile_number' => '6290731704',
        'company_gstin' => '19AADCW5913E1ZA',
        'address' => 'L.P-207/38/2, Sreenagar Paschim Para, Panchasayar',
        'city' => 'Kolkata',
        'pin' => '700094',
        'state' => 'West Bengal',
        'account_holder_name' => 'WISE DYNAMIC PRIVATE LIMITED',
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

    public function gstRates(): View
    {
        $rates = GstRate::with('slabs')
            ->orderBy('hsn_sac')
            ->paginate(15);

        return view('pos.gst-rates', compact('rates'));
    }

    public function storeGstRate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hsn_sac' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'gst_type' => ['required', 'in:simple,slab'],
            'simple_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'slabs' => ['nullable', 'array'],
            'slabs.*.min_amount' => ['nullable', 'numeric', 'min:0'],
            'slabs.*.max_amount' => ['nullable', 'numeric', 'min:0'],
            'slabs.*.rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($data['gst_type'] === 'simple' && (!isset($data['simple_rate']) || $data['simple_rate'] === null || $data['simple_rate'] === '')) {
            return back()->withErrors(['simple_rate' => 'GST rate is required for Simple type.'])->withInput();
        }

        if ($data['gst_type'] === 'slab') {
            $validSlabs = collect($data['slabs'] ?? [])
                ->filter(fn ($slab) => ($slab['min_amount'] ?? '') !== '' && ($slab['rate'] ?? '') !== '')
                ->values();

            if ($validSlabs->isEmpty()) {
                return back()->withErrors(['slabs' => 'At least one slab is required for Slab type.'])->withInput();
            }
        }

        DB::transaction(function () use ($data) {
            $rate = GstRate::updateOrCreate(
                ['hsn_sac' => $data['hsn_sac']],
                [
                    'description' => $data['description'] ?? null,
                    'gst_type' => $data['gst_type'],
                    'simple_rate' => $data['gst_type'] === 'simple' ? $data['simple_rate'] : null,
                ]
            );

            $rate->slabs()->delete();

            if ($data['gst_type'] === 'slab') {
                $slabs = collect($data['slabs'] ?? [])
                    ->filter(fn ($slab) => ($slab['min_amount'] ?? '') !== '' && ($slab['rate'] ?? '') !== '')
                    ->map(function ($slab) {
                        return [
                            'min_amount' => (float) $slab['min_amount'],
                            'max_amount' => ($slab['max_amount'] ?? '') !== '' ? (float) $slab['max_amount'] : null,
                            'rate' => (float) $slab['rate'],
                        ];
                    })
                    ->values()
                    ->all();

                if (!empty($slabs)) {
                    $rate->slabs()->createMany($slabs);
                }
            }
        });

        return redirect()->route('pos.settings.gst-rates')->with('success', 'GST rate saved successfully.');
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

    public function users(): View
    {
        $users = User::query()
            ->with('role:id,name')
            ->latest()
            ->paginate(15);
        $roles = Role::orderBy('name')->get(['id', 'name']);

        return view('pos.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        User::create($data);

        return redirect()->route('pos.settings.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user): View
    {
        $roles = Role::orderBy('name')->get(['id', 'name']);

        return view('pos.users-edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('pos.settings.users')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('pos.settings.users')->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('pos.settings.users')->with('success', 'User deleted successfully.');
    }

    public function roles(): View
    {
        $roles = Role::query()->orderBy('name')->paginate(15);

        return view('pos.roles', [
            'roles' => $roles,
            'functionOptions' => self::ROLE_FUNCTION_OPTIONS,
        ]);
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        Role::create([
            'name' => $data['name'],
            'permissions' => array_values($data['permissions'] ?? []),
        ]);

        return redirect()->route('pos.settings.roles')->with('success', 'Role created successfully.');
    }
}
