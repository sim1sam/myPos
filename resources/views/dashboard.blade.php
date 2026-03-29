@extends('layouts.pos-guest')

@section('title', 'Dashboard — ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-slate-100 to-slate-200/80 dark:from-slate-950 dark:to-slate-900">
        <header class="border-b border-slate-200/80 bg-white/90 shadow-sm backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/90">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
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

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Overview for today — replace with live POS metrics.</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-800 dark:bg-sky-950/50 dark:text-sky-200">
                    Logged in as {{ auth()->user()->name }}
                </span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <article class="pos-stat-card">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Today’s sales</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">—</p>
                    <p class="mt-1 text-xs text-slate-500">Connect your totals here</p>
                </article>
                <article class="pos-stat-card">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Transactions</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">—</p>
                    <p class="mt-1 text-xs text-slate-500">Receipt count</p>
                </article>
                <article class="pos-stat-card sm:col-span-2 lg:col-span-1">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Register</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Open</p>
                    <p class="mt-1 text-xs text-slate-500">Status placeholder</p>
                </article>
            </div>

            <section class="pos-stat-card mt-8 p-8" aria-labelledby="next-steps-heading">
                <h2 id="next-steps-heading" class="text-lg font-semibold text-slate-900 dark:text-white">Next steps</h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                    Add products, payment methods, and receipt printing. This layout uses Tailwind component classes from
                    <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs font-mono text-slate-800 dark:bg-slate-800 dark:text-slate-200">resources/css/app.css</code>
                    (<span class="font-mono text-xs">.pos-surface</span>, <span class="font-mono text-xs">.pos-input</span>, <span class="font-mono text-xs">.pos-btn-primary</span>).
                </p>
            </section>
        </main>
    </div>
@endsection
