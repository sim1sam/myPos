@extends('layouts.pos-app')

@section('title', 'Edit Customer — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-4xl">
        <div class="pos-dashboard-card">
            <h1 class="text-2xl font-semibold text-slate-800">Edit Customer</h1>
            <p class="mt-1 text-sm text-slate-500">Customer ID: {{ $customer->customer_code }}</p>

            <form method="POST" action="{{ route('pos.customers.update', $customer) }}" class="mt-6 grid gap-5 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div class="md:col-span-2">
                    <label for="name" class="pos-label">Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $customer->name) }}" required placeholder="Enter customer full name" class="pos-input @error('name') pos-input-error @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="pos-label">Address</label>
                    <textarea id="address" name="address" rows="3" placeholder="Flat/Shop no, street, area" class="pos-input @error('address') pos-input-error @enderror">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="pos-label">City</label>
                    <input id="city" name="city" type="text" value="{{ old('city', $customer->city) }}" placeholder="e.g. Ahmedabad" class="pos-input @error('city') pos-input-error @enderror">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pin_code" class="pos-label">Pin Code</label>
                    <input id="pin_code" name="pin_code" type="text" value="{{ old('pin_code', $customer->pin_code) }}" placeholder="e.g. 380001" class="pos-input @error('pin_code') pos-input-error @enderror">
                    @error('pin_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state" class="pos-label">State</label>
                    <input id="state" name="state" type="text" value="{{ old('state', $customer->state) }}" placeholder="e.g. Gujarat" class="pos-input @error('state') pos-input-error @enderror">
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="pos-label">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $customer->email) }}" placeholder="e.g. customer@email.com" class="pos-input @error('email') pos-input-error @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mobile" class="pos-label">Mobile</label>
                    <input id="mobile" name="mobile" type="text" value="{{ old('mobile', $customer->mobile) }}" placeholder="e.g. 9876543210" class="pos-input @error('mobile') pos-input-error @enderror">
                    @error('mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gstin" class="pos-label">GSTIN</label>
                    <input id="gstin" name="gstin" type="text" value="{{ old('gstin', $customer->gstin) }}" placeholder="e.g. 24ABCDE1234F1Z5" class="pos-input @error('gstin') pos-input-error @enderror">
                    @error('gstin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3 pt-1">
                    <a href="{{ route('pos.customers.index') }}" class="pos-btn-ghost">Cancel</a>
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Update Customer</button>
                </div>
            </form>
        </div>
    </section>
@endsection
