@extends('layouts.pos-app')

@section('title', 'Inventory List — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Inventory List</h1>
                <p class="mt-1 text-sm text-slate-500">Balance = Opening Stock + Purchases - Sales - Damage</p>
            </div>
            <div class="flex w-full max-w-3xl flex-wrap items-center justify-end gap-2">
                <form method="GET" action="{{ route('pos.stock.index') }}" class="flex w-full max-w-md items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search product, HSN/SAC, reference..."
                        class="pos-input py-2.5"
                    >
                    <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Search</button>
                    @if ($search)
                        <a href="{{ route('pos.stock.index') }}" class="pos-btn-ghost py-2.5">Clear</a>
                    @endif
                </form>
                <a href="{{ route('pos.stock.opening.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Opening Stock</a>
                <a href="{{ route('pos.stock.damage.create') }}" class="pos-btn-ghost py-2.5">Damage Entry</a>
            </div>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="text-lg font-semibold text-slate-800">Stock Summary</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">HSN/SAC</th>
                            <th class="px-4 py-3 font-semibold">Opening</th>
                            <th class="px-4 py-3 font-semibold">Purchased</th>
                            <th class="px-4 py-3 font-semibold">Sold</th>
                            <th class="px-4 py-3 font-semibold">Damage</th>
                            <th class="px-4 py-3 font-semibold">Balance</th>
                            <th class="px-4 py-3 font-semibold">Last Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($summary as $row)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $row['product_name'] }}</td>
                                <td class="px-4 py-3">{{ $row['hsn_sac'] }}</td>
                                <td class="px-4 py-3">{{ $row['opening_qty'] }}</td>
                                <td class="px-4 py-3">{{ $row['purchase_qty'] }}</td>
                                <td class="px-4 py-3">{{ $row['sold_qty'] }}</td>
                                <td class="px-4 py-3">{{ $row['damage_qty'] }}</td>
                                <td class="px-4 py-3 font-semibold {{ $row['balance_qty'] < 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                    {{ $row['balance_qty'] }}
                                </td>
                                <td class="px-4 py-3">Rs {{ number_format((float) ($row['last_price'] ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">No inventory records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="text-lg font-semibold text-slate-800">Movement Ledger</h2>
                <p class="mt-1 text-xs text-slate-500">Opening stock, purchases, sales, and damage entries</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Type</th>
                            <th class="px-4 py-3 font-semibold">Reference</th>
                            <th class="px-4 py-3 font-semibold">Vendor / Reason</th>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">HSN/SAC</th>
                            <th class="px-4 py-3 font-semibold">Price</th>
                            <th class="px-4 py-3 font-semibold">Qty In</th>
                            <th class="px-4 py-3 font-semibold">Qty Out</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($movements as $movement)
                            <tr>
                                <td class="px-4 py-3">{{ $movement['date'] ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $typeClass = match ($movement['type']) {
                                            'Opening Stock' => 'bg-sky-50 text-sky-700 ring-sky-100',
                                            'Purchase' => 'bg-indigo-50 text-indigo-700 ring-indigo-100',
                                            'Sale' => 'bg-amber-50 text-amber-700 ring-amber-100',
                                            'Damage' => 'bg-rose-50 text-rose-700 ring-rose-100',
                                            default => 'bg-slate-50 text-slate-700 ring-slate-100',
                                        };
                                    @endphp
                                    <span class="rounded-md px-2.5 py-1 text-xs font-medium ring-1 {{ $typeClass }}">{{ $movement['type'] }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $movement['reference'] ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $movement['vendor'] }}</td>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $movement['product_name'] }}</td>
                                <td class="px-4 py-3">{{ $movement['hsn_sac'] }}</td>
                                <td class="px-4 py-3">Rs {{ number_format((float) $movement['price'], 2) }}</td>
                                <td class="px-4 py-3 text-emerald-700">{{ $movement['qty_in'] > 0 ? $movement['qty_in'] : '-' }}</td>
                                <td class="px-4 py-3 text-rose-700">{{ $movement['qty_out'] > 0 ? $movement['qty_out'] : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-slate-500">No movement records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
