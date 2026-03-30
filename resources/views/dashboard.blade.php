@extends('layouts.pos-app')

@section('title', 'Dashboard — ' . config('app.name'))

@section('page-content')
    <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <a href="{{ route('pos.sales') }}" class="pos-dashboard-card group">
            <span class="pos-chip">POS</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">POS</h2>
            <p class="mt-1 text-sm text-slate-500">Create a new invoice</p>
        </a>
        <a href="{{ route('pos.products') }}" class="pos-dashboard-card group">
            <span class="pos-chip">INV</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Invoices</h2>
            <p class="mt-1 text-sm text-slate-500">View all invoices</p>
        </a>
        <a href="{{ route('pos.customers') }}" class="pos-dashboard-card group">
            <span class="pos-chip">CUS</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Customers</h2>
            <p class="mt-1 text-sm text-slate-500">Manage all customers</p>
        </a>
        <a href="{{ route('pos.inventory') }}" class="pos-dashboard-card group">
            <span class="pos-chip">LED</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Customer Ledger</h2>
            <p class="mt-1 text-sm text-slate-500">Track customer transactions</p>
        </a>

        <article class="pos-dashboard-card">
            <span class="pos-chip">SUM</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Total Invoice Amount</h2>
            <p class="mt-1 text-sm text-slate-500">Rs 15,386,191.88</p>
        </article>
        <article class="pos-dashboard-card">
            <span class="pos-chip">REC</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Total Payment Received</h2>
            <p class="mt-1 text-sm text-slate-500">Rs 5,295,850.00</p>
        </article>
        <article class="pos-dashboard-card">
            <span class="pos-chip">DUE</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Total Due Amount</h2>
            <p class="mt-1 text-sm text-slate-500">Rs 10,090,344.88</p>
        </article>
        <a href="{{ route('pos.reports') }}" class="pos-dashboard-card group">
            <span class="pos-chip">GST</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">GST Report</h2>
            <p class="mt-1 text-sm text-slate-500">View GST report</p>
        </a>

        <a href="{{ route('pos.inventory') }}" class="pos-dashboard-card group">
            <span class="pos-chip">ADD</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Add Payment</h2>
            <p class="mt-1 text-sm text-slate-500">Record a new customer payment</p>
        </a>
        <a href="{{ route('pos.inventory') }}" class="pos-dashboard-card group">
            <span class="pos-chip">PAY</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Payments</h2>
            <p class="mt-1 text-sm text-slate-500">View all payments</p>
        </a>
        <a href="{{ route('pos.settings') }}" class="pos-dashboard-card group">
            <span class="pos-chip">CFG</span>
            <h2 class="mt-2 text-lg font-semibold text-slate-800">Settings</h2>
            <p class="mt-1 text-sm text-slate-500">Configure company settings</p>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="pos-dashboard-card w-full text-left">
                <span class="pos-chip">OUT</span>
                <h2 class="mt-2 text-lg font-semibold text-slate-800">Logout</h2>
                <p class="mt-1 text-sm text-slate-500">Sign out from this session</p>
            </button>
        </form>
    </section>
@endsection
