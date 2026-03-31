@extends('layouts.pos-app')

@section('title', 'Edit Expense — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-5xl">
        <div class="pos-dashboard-card">
            <div class="flex items-center justify-between gap-3">
                <h1 class="text-2xl font-semibold text-slate-800">Edit Expense</h1>
                <a href="{{ route('pos.expenses.list') }}" class="pos-btn-ghost">Back</a>
            </div>

            <form method="POST" action="{{ route('pos.expenses.update', $expense) }}" class="mt-6 grid gap-5 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label for="expense_date" class="pos-label">Date *</label>
                    <input id="expense_date" name="expense_date" type="date" value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}" class="pos-input @error('expense_date') pos-input-error @enderror" required>
                    @error('expense_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="amount" class="pos-label">Amount *</label>
                    <input id="amount" name="amount" type="number" min="0.01" step="0.01" value="{{ old('amount', $expense->amount) }}" class="pos-input @error('amount') pos-input-error @enderror" required>
                    @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="expense_head_id" class="pos-label">Expense Head *</label>
                    <select id="expense_head_id" name="expense_head_id" class="pos-input @error('expense_head_id') pos-input-error @enderror" required>
                        <option value="">Select Expense Head</option>
                        @foreach ($expenseHeads as $head)
                            <option value="{{ $head->id }}" @selected(old('expense_head_id', $expense->expense_head_id) == $head->id)>{{ $head->name }}</option>
                        @endforeach
                    </select>
                    @error('expense_head_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="payment_mode_id" class="pos-label">Payment Mode *</label>
                    <select id="payment_mode_id" name="payment_mode_id" class="pos-input @error('payment_mode_id') pos-input-error @enderror" required>
                        <option value="">Select Payment Mode</option>
                        @foreach ($paymentModes as $mode)
                            <option value="{{ $mode->id }}" @selected(old('payment_mode_id', $expense->payment_mode_id) == $mode->id)>{{ $mode->name }}</option>
                        @endforeach
                    </select>
                    @error('payment_mode_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="remarks" class="pos-label">Remarks</label>
                    <textarea id="remarks" name="remarks" rows="3" class="pos-input @error('remarks') pos-input-error @enderror">{{ old('remarks', $expense->remarks) }}</textarea>
                    @error('remarks')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Update Expense</button>
                </div>
            </form>
        </div>
    </section>
@endsection
