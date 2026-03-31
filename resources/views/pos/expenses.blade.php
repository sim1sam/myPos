@extends('layouts.pos-app')

@section('title', 'Expenses — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Expenses</h1>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('pos.expenses.create') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75h-13.5" />
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Create Expense</h2>
                <p class="mt-2 text-sm text-slate-500">Add a new expense entry.</p>
            </a>

            <a href="{{ route('pos.expenses.list') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <rect x="3.75" y="4.5" width="16.5" height="15" rx="1.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9"/>
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Expense List</h2>
                <p class="mt-2 text-sm text-slate-500">View all recorded expenses.</p>
            </a>

            <a href="{{ route('pos.expenses.heads') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15m-15 4.5h15m-15 4.5h9" />
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Expense Head</h2>
                <p class="mt-2 text-sm text-slate-500">Manage expense categories/heads.</p>
            </a>
        </div>
    </section>
@endsection
