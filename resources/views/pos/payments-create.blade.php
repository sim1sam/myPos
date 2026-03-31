@extends('layouts.pos-app')

@section('title', 'Add Payment — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-5xl">
        <div class="pos-dashboard-card">
            <div class="flex items-center justify-between gap-3">
                <h1 class="text-2xl font-semibold text-slate-800">Add Payment</h1>
                <div class="flex items-center gap-2">
                    <a href="{{ route('pos.payments.index') }}" class="pos-btn-ghost">All Payments</a>
                    <a href="{{ route('pos.settings.payment-modes.index') }}" class="pos-btn-ghost">Payment Modes</a>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('pos.payments.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="pos-label" for="customer_id">Customer</label>
                    <select id="customer_id" name="customer_id" class="pos-input @error('customer_id') pos-input-error @enderror" required>
                        <option value="">-- Select Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                {{ $customer->customer_code }} - {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="pos-label" for="amount">Amount (Rs)</label>
                    <input id="amount" name="amount" type="number" min="1" step="0.01" value="{{ old('amount') }}"
                           class="pos-input @error('amount') pos-input-error @enderror" placeholder="Enter amount" required>
                    @error('amount')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="pos-label" for="payment_mode_id">Payment Mode</label>
                    <select id="payment_mode_id" name="payment_mode_id" class="pos-input @error('payment_mode_id') pos-input-error @enderror" required>
                        <option value="">-- Select Mode --</option>
                        @foreach ($paymentModes as $mode)
                            <option value="{{ $mode->id }}" @selected(old('payment_mode_id') == $mode->id)>{{ $mode->name }}</option>
                        @endforeach
                    </select>
                    @if ($paymentModes->isEmpty())
                        <p class="mt-1 text-xs text-amber-700">No payment mode available. Please add one in Settings -> Payment Modes.</p>
                    @endif
                    @error('payment_mode_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="pos-label" for="payment_date">Payment Date</label>
                    <input id="payment_date" name="payment_date" type="date" value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                           class="pos-input @error('payment_date') pos-input-error @enderror" required>
                    @error('payment_date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="pos-label" for="note">Note (optional)</label>
                    <textarea id="note" name="note" rows="3" class="pos-input @error('note') pos-input-error @enderror"
                              placeholder="Enter note">{{ old('note') }}</textarea>
                    @error('note')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Create Payment</button>
                </div>
            </form>
        </div>
    </section>
@endsection
