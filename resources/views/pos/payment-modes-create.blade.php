@extends('layouts.pos-app')

@section('title', 'Create Payment Mode — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-3xl">
        <div class="pos-dashboard-card">
            <div class="flex items-center justify-between gap-3">
                <h1 class="text-2xl font-semibold text-slate-800">Create Payment Mode</h1>
                <a href="{{ route('pos.settings.payment-modes.index') }}" class="pos-btn-ghost">Back to List</a>
            </div>

            <form method="POST" action="{{ route('pos.settings.payment-modes.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="pos-label" for="name">Payment Mode Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                           class="pos-input @error('name') pos-input-error @enderror"
                           placeholder="Enter payment mode name" required>
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Create</button>
                </div>
            </form>
        </div>
    </section>
@endsection
