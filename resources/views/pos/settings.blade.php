@extends('layouts.pos-app')

@section('title', 'Settings — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Settings</h1>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('pos.settings.gst-rates') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <rect x="3.75" y="4.5" width="16.5" height="15" rx="1.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9M7.5 17.25h5.25"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">GST Rates</h2>
                <p class="mt-2 text-sm text-slate-500">Manage HSN/SAC and tax rates.</p>
            </a>

            <a href="{{ route('pos.settings.payment-modes.index') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h12A2.25 2.25 0 0 1 20.25 6.75v10.5A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 12h9"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Payment Modes</h2>
                <p class="mt-2 text-sm text-slate-500">Configure available payment types.</p>
            </a>

            <a href="{{ route('pos.settings.users') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0 1 15 0"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Users</h2>
                <p class="mt-2 text-sm text-slate-500">Manage system users and roles.</p>
            </a>

            <a href="{{ route('pos.settings.company-profile') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9h3.75m-3.75 3.75h9m-9 3.75h9"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Company Profile</h2>
                <p class="mt-2 text-sm text-slate-500">Manage your company profile and info.</p>
            </a>
        </div>
    </section>
@endsection
