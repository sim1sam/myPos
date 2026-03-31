@extends('layouts.pos-app')

@section('title', 'Settings — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Settings</h1>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('pos.settings.payment-modes.index') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h12A2.25 2.25 0 0 1 20.25 6.75v10.5A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 12h9"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Payment Modes</h2>
                <p class="mt-2 text-sm text-slate-500">Manage payment mode list used in Add Payment form.</p>
            </a>
        </div>
    </section>
@endsection
