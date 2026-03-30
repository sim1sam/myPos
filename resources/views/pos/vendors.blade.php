@extends('layouts.pos-app')

@section('title', 'Vendor Module — ' . config('app.name'))

@section('page-content')
    <section>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Vendor Dashboard</h1>
        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 grid gap-5 md:grid-cols-2">
            <a href="{{ route('pos.vendors.create') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75h-13.5" />
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">Create Vendor</h2>
                <p class="mt-2 text-sm text-slate-500">Add vendor profile with GST, bank details, document, and payment number.</p>
            </a>

            <a href="{{ route('pos.vendors.index') }}" class="pos-dashboard-card group">
                <span class="pos-card-icon pos-card-icon-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9" />
                    </svg>
                </span>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">List Vendors</h2>
                <p class="mt-2 text-sm text-slate-500">View vendor records and payment/bank details.</p>
            </a>
        </div>
    </section>
@endsection
