@extends('layouts.pos-app')

@section('title', 'Customers — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Customer Dashboard</h1>
        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('pos.customers.create') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75h-13.5" />
                    </svg>
                </span>
                <h2 class="mt-1 text-2xl font-semibold text-slate-800">Add Customer</h2>
                <p class="mt-2 text-sm text-slate-500">Register a new customer with contact and GST details.</p>
            </a>

            <a href="{{ route('pos.customers.index') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0 1 15 0" />
                    </svg>
                </span>
                <h2 class="mt-1 text-2xl font-semibold text-slate-800">All Customers</h2>
                <p class="mt-2 text-sm text-slate-500">View, edit, or filter the full customer list.</p>
            </a>

            <a href="{{ route('pos.customers.summary') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <rect x="3.75" y="4.5" width="16.5" height="15" rx="1.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15.75V12m4.5 3.75V9m4.5 6.75v-5.25" />
                    </svg>
                </span>
                <h2 class="mt-1 text-2xl font-semibold text-slate-800">Invoice & Payment Summary</h2>
                <p class="mt-2 text-sm text-slate-500">Check customer-wise billing and payment totals.</p>
            </a>
        </div>
    </section>
@endsection
