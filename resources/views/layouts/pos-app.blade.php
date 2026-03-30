<!DOCTYPE html>
<html class="min-h-screen scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name'))</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="pos-app-bg min-h-screen font-sans text-slate-800 antialiased">
        @php
            $menu = [
                ['label' => 'Home', 'route' => 'dashboard'],
                ['label' => 'POS', 'route' => 'pos.sales'],
                ['label' => 'Invoices', 'route' => 'pos.products'],
                ['label' => 'Customers', 'route' => 'pos.customers'],
                ['label' => 'Add Payment', 'route' => 'pos.inventory'],
                ['label' => 'Settings', 'route' => 'pos.settings'],
            ];
        @endphp

        <div class="flex min-h-screen flex-col">
            <header class="pos-topbar">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sky-800">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-linear-to-br from-sky-600 to-blue-700 text-[10px] font-bold text-white">WD</span>
                        <span class="text-sm font-semibold tracking-wide text-sky-900">{{ strtoupper(config('app.name')) }}</span>
                    </a>

                    <nav class="flex flex-wrap items-center gap-3 text-xs sm:text-sm" aria-label="Top menu">
                        @foreach ($menu as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                @class([
                                    'pos-top-link',
                                    'pos-top-link-active' => request()->routeIs($item['route']),
                                ])
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="rounded-md bg-rose-50 px-2 py-1 text-rose-600 transition hover:bg-rose-100 hover:text-rose-700">Logout</button>
                        </form>
                    </nav>
                </div>
            </header>

            <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-10 sm:px-6 lg:px-8">
                @yield('page-content')
            </main>

            <footer class="mt-auto border-t border-sky-100 bg-white/80 backdrop-blur-sm">
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3 text-xs text-slate-500 sm:px-6 lg:px-8">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                    <p>Designed for POS workflow</p>
                </div>
            </footer>
        </div>
    </body>
</html>
