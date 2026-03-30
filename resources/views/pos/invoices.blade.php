@extends('layouts.pos-app')

@section('title', 'Invoices — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Invoice Dashboard</h1>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('pos.invoices.create') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3.75h10.5a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5V5.25a1.5 1.5 0 0 1 1.5-1.5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.5h6m-6 3h6m-6 3h3.75"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Create Invoice</h2>
                <p class="mt-2 text-sm text-slate-500">Create new invoice with customer, GST type, and line items.</p>
            </a>

            <a href="{{ route('pos.invoices.index') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">All Invoices</h2>
                <p class="mt-2 text-sm text-slate-500">View all invoices under one list page.</p>
            </a>

            <a href="{{ route('pos.invoices.index', ['scope' => 'gst']) }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <rect x="3.75" y="4.5" width="16.5" height="15" rx="1.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15.75V12m4.5 3.75V9m4.5 6.75v-5.25"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">GST Invoices</h2>
                <p class="mt-2 text-sm text-slate-500">Open GST invoice list and tax-related invoices.</p>
            </a>

            <a href="{{ route('pos.reports') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15.75V12m4.5 3.75V9m4.5 6.75v-5.25"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">GST Report</h2>
                <p class="mt-2 text-sm text-slate-500">View GST report card and tax summary page.</p>
            </a>
        </div>
    </section>
@endsection
