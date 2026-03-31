@extends('layouts.pos-app')

@section('title', 'Company Profile — ' . config('app.name'))

@section('page-content')
    @php
        $profileData = array_merge($defaults ?? [], $profile?->toArray() ?? []);
    @endphp

    <section class="mx-auto max-w-5xl">
        <div class="pos-dashboard-card">
            <h1 class="text-2xl font-semibold text-slate-800">Company Profile</h1>

            @if (session('success'))
                <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('pos.settings.company-profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="pos-label" for="logo">Company Logo (Max 2MB)</label>
                        <input id="logo" name="logo" type="file" accept="image/*" class="pos-input">
                        @if ($profile?->logo_path)
                            <img src="{{ asset($profile->logo_path) }}" alt="Company Logo" class="mt-2 h-16 rounded border border-slate-200 bg-white p-1">
                        @endif
                        @error('logo')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="pos-label" for="qr_code">QR Code (Max 2MB)</label>
                        <input id="qr_code" name="qr_code" type="file" accept="image/*" class="pos-input">
                        @if ($profile?->qr_code_path)
                            <img src="{{ asset($profile->qr_code_path) }}" alt="QR Code" class="mt-2 h-16 rounded border border-slate-200 bg-white p-1">
                        @endif
                        @error('qr_code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="pos-label" for="company_name">Company Name</label>
                        <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $profileData['company_name'] ?? '') }}" class="pos-input" required>
                    </div>
                    <div>
                        <label class="pos-label" for="company_email">Company Email</label>
                        <input id="company_email" name="company_email" type="email" value="{{ old('company_email', $profileData['company_email'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="mobile_number">Mobile Number</label>
                        <input id="mobile_number" name="mobile_number" type="text" value="{{ old('mobile_number', $profileData['mobile_number'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="company_gstin">Company GSTIN</label>
                        <input id="company_gstin" name="company_gstin" type="text" value="{{ old('company_gstin', $profileData['company_gstin'] ?? '') }}" class="pos-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="pos-label" for="address">Address</label>
                        <textarea id="address" name="address" rows="2" class="pos-input">{{ old('address', $profileData['address'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="pos-label" for="city">City</label>
                        <input id="city" name="city" type="text" value="{{ old('city', $profileData['city'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="pin">PIN</label>
                        <input id="pin" name="pin" type="text" value="{{ old('pin', $profileData['pin'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="state">State</label>
                        <input id="state" name="state" type="text" value="{{ old('state', $profileData['state'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="account_holder_name">A/C Holder Name</label>
                        <input id="account_holder_name" name="account_holder_name" type="text" value="{{ old('account_holder_name', $profileData['account_holder_name'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="account_number">Account Number</label>
                        <input id="account_number" name="account_number" type="text" value="{{ old('account_number', $profileData['account_number'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="bank_name">Bank Name</label>
                        <input id="bank_name" name="bank_name" type="text" value="{{ old('bank_name', $profileData['bank_name'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="branch">Branch</label>
                        <input id="branch" name="branch" type="text" value="{{ old('branch', $profileData['branch'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="ifsc_code">IFSC Code</label>
                        <input id="ifsc_code" name="ifsc_code" type="text" value="{{ old('ifsc_code', $profileData['ifsc_code'] ?? '') }}" class="pos-input">
                    </div>
                    <div>
                        <label class="pos-label" for="company_pan">Company PAN</label>
                        <input id="company_pan" name="company_pan" type="text" value="{{ old('company_pan', $profileData['company_pan'] ?? '') }}" class="pos-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="pos-label" for="declaration">Declaration</label>
                        <textarea id="declaration" name="declaration" rows="2" class="pos-input">{{ old('declaration', $profileData['declaration'] ?? '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="pos-label" for="footer_text">Footer Text</label>
                        <textarea id="footer_text" name="footer_text" rows="2" class="pos-input">{{ old('footer_text', $profileData['footer_text'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Save Profile</button>
                </div>
            </form>
        </div>
    </section>
@endsection
