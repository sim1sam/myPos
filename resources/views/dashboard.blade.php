@extends('layouts.pos-guest')

@section('title', 'Dashboard — ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-slate-100 to-slate-200/80 dark:from-slate-950 dark:to-slate-900">
        <header class="border-b border-slate-200/80 bg-white/90 shadow-sm backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/90">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-md shadow-sky-900/25">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3H15m7 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-base font-semibold text-slate-900 dark:text-white">{{ config('app.name') }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="pos-btn-ghost">Sign out</button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">POS Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Track sales, register status, and latest transactions.</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-800 dark:bg-sky-950/50 dark:text-sky-200">
                    Logged in as {{ auth()->user()->name }}
                </span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="pos-stat-card">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Today's sales</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">$4,250.75</p>
                    <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">+11.4% vs yesterday</p>
                </article>
                <article class="pos-stat-card">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Transactions today</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">128</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Average ticket $33.21</p>
                </article>
                <article class="pos-stat-card">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Items low stock</p>
                    <p class="mt-2 text-2xl font-semibold text-amber-600 dark:text-amber-400">9</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Needs restock alert</p>
                </article>
                <article class="pos-stat-card">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Register</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Open</p>
                    <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">Cash drawer balanced</p>
                </article>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                <section class="pos-stat-card lg:col-span-2" aria-labelledby="quick-actions-heading">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 id="quick-actions-heading" class="text-lg font-semibold text-slate-900 dark:text-white">Quick actions</h2>
                        <span class="text-xs text-slate-500 dark:text-slate-400">POS shortcuts</span>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <button type="button" class="pos-btn-ghost justify-between">
                            New Sale
                            <span aria-hidden="true">-></span>
                        </button>
                        <button type="button" class="pos-btn-ghost justify-between">
                            Add Product
                            <span aria-hidden="true">-></span>
                        </button>
                        <button type="button" class="pos-btn-ghost justify-between">
                            Open Register
                            <span aria-hidden="true">-></span>
                        </button>
                        <button type="button" class="pos-btn-ghost justify-between">
                            Daily Report
                            <span aria-hidden="true">-></span>
                        </button>
                    </div>
                </section>

                <aside class="pos-stat-card" aria-labelledby="cash-summary-heading">
                    <h2 id="cash-summary-heading" class="text-lg font-semibold text-slate-900 dark:text-white">Cash summary</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500 dark:text-slate-400">Opening float</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">$500.00</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500 dark:text-slate-400">Cash sales</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">$1,820.00</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500 dark:text-slate-400">Paid out</dt>
                            <dd class="font-medium text-rose-600 dark:text-rose-400">$55.00</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-200 pt-3 dark:border-slate-700">
                            <dt class="font-semibold text-slate-700 dark:text-slate-200">Expected in drawer</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">$2,265.00</dd>
                        </div>
                    </dl>
                </aside>
            </div>

            <section class="pos-stat-card mt-6" aria-labelledby="orders-heading">
                <div class="mb-4 flex items-center justify-between">
                    <h2 id="orders-heading" class="text-lg font-semibold text-slate-900 dark:text-white">Recent orders</h2>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Latest 5</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                <th class="px-2 py-2">Order</th>
                                <th class="px-2 py-2">Cashier</th>
                                <th class="px-2 py-2">Items</th>
                                <th class="px-2 py-2">Total</th>
                                <th class="px-2 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr>
                                <td class="px-2 py-2 font-medium text-slate-900 dark:text-white">#INV-1042</td>
                                <td class="px-2 py-2 text-slate-600 dark:text-slate-300">Admin</td>
                                <td class="px-2 py-2 text-slate-600 dark:text-slate-300">5</td>
                                <td class="px-2 py-2 text-slate-900 dark:text-white">$82.00</td>
                                <td class="px-2 py-2"><span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">Paid</span></td>
                            </tr>
                            <tr>
                                <td class="px-2 py-2 font-medium text-slate-900 dark:text-white">#INV-1041</td>
                                <td class="px-2 py-2 text-slate-600 dark:text-slate-300">Admin</td>
                                <td class="px-2 py-2 text-slate-600 dark:text-slate-300">2</td>
                                <td class="px-2 py-2 text-slate-900 dark:text-white">$19.50</td>
                                <td class="px-2 py-2"><span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">Paid</span></td>
                            </tr>
                            <tr>
                                <td class="px-2 py-2 font-medium text-slate-900 dark:text-white">#INV-1040</td>
                                <td class="px-2 py-2 text-slate-600 dark:text-slate-300">Admin</td>
                                <td class="px-2 py-2 text-slate-600 dark:text-slate-300">7</td>
                                <td class="px-2 py-2 text-slate-900 dark:text-white">$136.20</td>
                                <td class="px-2 py-2"><span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-950/40 dark:text-amber-300">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
@endsection
