@extends('layouts.pos-app')

@section('title', 'Inventory — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Inventory</h1>
        <p class="mt-1 text-sm text-slate-500">Opening stock sets initial inventory. Purchases add stock, sales deduct, and damage entries reduce stock.</p>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('pos.stock.opening.create') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75h-13.5" />
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Opening Stock</h2>
                <p class="mt-2 text-sm text-slate-500">Enter initial stock with multiple items, similar to purchase entry fields.</p>
            </a>

            <a href="{{ route('pos.stock.damage.create') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Damage Entry</h2>
                <p class="mt-2 text-sm text-slate-500">Record damaged or lost stock to deduct from inventory balance.</p>
            </a>

            <a href="{{ route('pos.stock.index') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9" />
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Inventory List</h2>
                <p class="mt-2 text-sm text-slate-500">View opening stock, purchases, sales, damage, and current balance.</p>
            </a>
        </div>
    </section>
@endsection
