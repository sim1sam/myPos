@extends('layouts.pos-app')

@section('title', 'Dashboard — ' . config('app.name'))

@section('page-content')
    <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <a href="{{ route('pos.sales') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3.75h10.5a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5V5.25a1.5 1.5 0 0 1 1.5-1.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 7.5h6m-6 3h6m-6 3h3.75"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">POS</h2>
            <p class="mt-1 text-sm text-slate-500">Create a new invoice</p>
        </a>
        <a href="{{ route('pos.products') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon pos-card-icon-gold"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h4.784c.597 0 1.169.237 1.591.659l1.216 1.216c.422.422.994.659 1.591.659H18A2.25 2.25 0 0 1 20.25 9.3v7.95A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Invoices</h2>
            <p class="mt-1 text-sm text-slate-500">View all invoices</p>
        </a>
        <a href="{{ route('pos.purchases') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Purchase</h2>
            <p class="mt-1 text-sm text-slate-500">Create and manage purchases</p>
        </a>
        <a href="{{ route('pos.vendors') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon pos-card-icon-gold"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6h15m-13.5 4.5h12m-12 4.5h12m-12 4.5h9"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Vendor</h2>
            <p class="mt-1 text-sm text-slate-500">Create and manage vendors</p>
        </a>
        <a href="{{ route('pos.customers') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0 1 15 0"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Customers</h2>
            <p class="mt-1 text-sm text-slate-500">Manage all customers</p>
        </a>
        <a href="{{ route('pos.customers.ledger') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon pos-card-icon-gold"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 3.75h15A1.5 1.5 0 0 1 21 5.25v13.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18.75V5.25a1.5 1.5 0 0 1 1.5-1.5Z"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Customer Ledger</h2>
            <p class="mt-1 text-sm text-slate-500">Track customer transactions</p>
        </a>

        <article class="pos-dashboard-card">
            <span class="pos-card-icon pos-card-icon-gold"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m3-9H9m6 6H9"/><circle cx="12" cy="12" r="9" /></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Total Invoice Amount</h2>
            <p class="mt-1 text-sm text-slate-500">Rs {{ number_format((float) ($totalInvoiceAmount ?? 0), 2) }}</p>
        </article>
        <article class="pos-dashboard-card">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5h18"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Total Payment Received</h2>
            <p class="mt-1 text-sm text-slate-500">Rs {{ number_format((float) ($totalPaymentReceived ?? 0), 2) }}</p>
        </article>
        <article class="pos-dashboard-card">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v4.5l3 1.5"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Total Due Amount</h2>
            <p class="mt-1 text-sm text-slate-500">Rs {{ number_format((float) ($totalDueAmount ?? 0), 2) }}</p>
        </article>
        <a href="{{ route('pos.reports') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3.75" y="4.5" width="16.5" height="15" rx="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15.75V12m4.5 3.75V9m4.5 6.75v-5.25"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">GST Report</h2>
            <p class="mt-1 text-sm text-slate-500">View GST report</p>
        </a>

        <a href="{{ route('pos.inventory') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75h-13.5"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Add Payment</h2>
            <p class="mt-1 text-sm text-slate-500">Record a new customer payment</p>
        </a>
        <a href="{{ route('pos.payments.index') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon pos-card-icon-gold"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="2.25" y="6.75" width="19.5" height="10.5" rx="1.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 10.5h19.5"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Payments</h2>
            <p class="mt-1 text-sm text-slate-500">View all payments</p>
        </a>
        <a href="{{ route('pos.settings') }}" class="pos-dashboard-card group">
            <span class="pos-card-icon"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-7.5 6h12m-9 6h6"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h3m0 0a1.5 1.5 0 1 0 3 0m-3 0H3m18 6h-3m0 0a1.5 1.5 0 1 0-3 0m3 0h3M3 18h6m0 0a1.5 1.5 0 1 0 3 0m-3 0H3"/></svg></span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Settings</h2>
            <p class="mt-1 text-sm text-slate-500">Configure company settings</p>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="pos-dashboard-card w-full text-left">
                <span class="pos-card-icon pos-card-icon-gold"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 12h9m0 0-3-3m3 3-3 3"/></svg></span>
                <h2 class="mt-2 text-lg font-semibold text-slate-800">Logout</h2>
                <p class="mt-1 text-sm text-slate-500">Sign out from this session</p>
            </button>
        </form>
    </section>
@endsection
