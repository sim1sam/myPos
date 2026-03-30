@extends('layouts.pos-app')

@section('title', 'Edit Vendor — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-5xl">
        <div class="pos-dashboard-card">
            <h1 class="text-2xl font-semibold text-slate-800">Edit Vendor</h1>
            <p class="mt-1 text-sm text-slate-500">Vendor ID: {{ $vendor->vendor_code }}</p>

            <form method="POST" action="{{ route('pos.vendors.update', $vendor) }}" enctype="multipart/form-data" class="mt-6 grid gap-5 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="pos-label">Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $vendor->name) }}" required class="pos-input @error('name') pos-input-error @enderror">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="gstin" class="pos-label">GSTIN</label>
                    <input id="gstin" name="gstin" type="text" value="{{ old('gstin', $vendor->gstin) }}" class="pos-input @error('gstin') pos-input-error @enderror">
                    @error('gstin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="mobile_no" class="pos-label">Mobile No</label>
                    <input id="mobile_no" name="mobile_no" type="text" value="{{ old('mobile_no', $vendor->mobile_no) }}" class="pos-input @error('mobile_no') pos-input-error @enderror">
                    @error('mobile_no')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="gpay_phonepay_no" class="pos-label">GPay / PhonePe No</label>
                    <input id="gpay_phonepay_no" name="gpay_phonepay_no" type="text" value="{{ old('gpay_phonepay_no', $vendor->gpay_phonepay_no) }}" class="pos-input @error('gpay_phonepay_no') pos-input-error @enderror">
                    @error('gpay_phonepay_no')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="account_name" class="pos-label">Account Name</label>
                    <input id="account_name" name="account_name" type="text" value="{{ old('account_name', $vendor->account_name) }}" class="pos-input @error('account_name') pos-input-error @enderror">
                    @error('account_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="account_no" class="pos-label">A/C No</label>
                    <input id="account_no" name="account_no" type="text" value="{{ old('account_no', $vendor->account_no) }}" class="pos-input @error('account_no') pos-input-error @enderror">
                    @error('account_no')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="ifsc" class="pos-label">IFSC</label>
                    <input id="ifsc" name="ifsc" type="text" value="{{ old('ifsc', $vendor->ifsc) }}" class="pos-input @error('ifsc') pos-input-error @enderror">
                    @error('ifsc')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="bank_name" class="pos-label">Bank Name</label>
                    <input id="bank_name" name="bank_name" type="text" value="{{ old('bank_name', $vendor->bank_name) }}" class="pos-input @error('bank_name') pos-input-error @enderror">
                    @error('bank_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="branch_name" class="pos-label">Branch Name</label>
                    <input id="branch_name" name="branch_name" type="text" value="{{ old('branch_name', $vendor->branch_name) }}" class="pos-input @error('branch_name') pos-input-error @enderror">
                    @error('branch_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="document" class="pos-label">Upload New PDF/Image</label>
                    <input id="document" name="document" type="file" accept=".pdf,.jpg,.jpeg,.png,.webp" class="pos-input @error('document') pos-input-error @enderror">
                    @error('document')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3">
                    <a href="{{ route('pos.vendors.index') }}" class="pos-btn-ghost">Cancel</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Update Vendor</button>
                </div>
            </form>
        </div>
    </section>
@endsection
