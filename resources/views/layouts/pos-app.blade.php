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
            $companyLogo = \App\Models\CompanyProfile::query()->value('logo_path');
            $userPermissions = auth()->user()?->role?->permissions ?? [];
            $hasFullAccess = !auth()->user()?->role;
            $menuPermissionMap = [
                'dashboard' => 'dashboard.view',
                'pos.sales' => 'sales.create',
                'pos.purchases' => 'purchases.view',
                'pos.vendors' => 'vendors.view',
                'pos.products' => 'invoices.dashboard',
                'pos.customers' => 'customers.view',
                'pos.payments.index' => 'payments.view',
                'pos.settings' => 'settings.view',
            ];
            $legacyMenuPermissionMap = [
                'dashboard.view' => 'dashboard',
                'sales.create' => 'pos',
                'purchases.view' => 'purchases',
                'vendors.view' => 'vendors',
                'invoices.dashboard' => 'invoices',
                'customers.view' => 'customers',
                'payments.view' => 'payments',
                'settings.view' => 'settings',
            ];
            $menu = [
                ['label' => 'Home', 'route' => 'dashboard'],
                ['label' => 'POS', 'route' => 'pos.sales'],
                ['label' => 'Purchase', 'route' => 'pos.purchases'],
                ['label' => 'Vendor', 'route' => 'pos.vendors'],
                ['label' => 'Invoices', 'route' => 'pos.products'],
                ['label' => 'Customers', 'route' => 'pos.customers'],
                ['label' => 'Add Payment', 'route' => 'pos.payments.index'],
                ['label' => 'Settings', 'route' => 'pos.settings'],
            ];
            $menu = collect($menu)->filter(function ($item) use ($hasFullAccess, $userPermissions, $menuPermissionMap, $legacyMenuPermissionMap) {
                if ($hasFullAccess) {
                    return true;
                }
                $permission = $menuPermissionMap[$item['route']] ?? null;
                if (!$permission) {
                    return true;
                }
                if (in_array($permission, $userPermissions, true)) {
                    return true;
                }
                $legacyPermission = $legacyMenuPermissionMap[$permission] ?? null;
                return $legacyPermission ? in_array($legacyPermission, $userPermissions, true) : false;
            })->values()->all();
        @endphp

        <div class="flex min-h-screen flex-col">
            <header class="pos-topbar">
                <div class="mx-auto max-w-screen-2xl px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sky-800">
                        <span class="flex h-8 w-auto items-center justify-center overflow-hidden rounded-none bg-transparent text-[10px] font-bold text-white">
                            @if ($companyLogo)
                                <img src="{{ asset($companyLogo) }}" alt="Company Logo" class="h-8 w-auto object-contain">
                            @else
                                WD
                            @endif
                        </span>
                    </a>

                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-md bg-sky-50 p-2 text-sky-700 ring-1 ring-sky-100 md:hidden"
                            aria-label="Open menu"
                            aria-controls="mobile-menu"
                            aria-expanded="false"
                            onclick="const menu=document.getElementById('mobile-menu'); const expanded=this.getAttribute('aria-expanded')==='true'; this.setAttribute('aria-expanded', expanded ? 'false' : 'true'); menu.classList.toggle('hidden');"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <nav class="hidden items-center gap-3 text-xs sm:text-sm md:flex" aria-label="Top menu">
                        @foreach ($menu as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                @class([
                                    'pos-top-link',
                                    'pos-top-link-active' => request()->routeIs($item['route']),
                                ])
                            >
                                <span class="inline-flex items-center gap-1.5">
                                    @if ($item['route'] === 'dashboard')
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 9 8.25-6 8.25 6v10.5A1.5 1.5 0 0 1 18.75 21h-13.5a1.5 1.5 0 0 1-1.5-1.5V9Z" /></svg>
                                    @elseif ($item['route'] === 'pos.sales')
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h12A2.25 2.25 0 0 1 20.25 6.75v10.5A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z" /></svg>
                                    @elseif ($item['route'] === 'pos.products')
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h4.784c.597 0 1.169.237 1.591.659l1.216 1.216c.422.422.994.659 1.591.659H18a2.25 2.25 0 0 1 2.25 2.25v7.966A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z" /></svg>
                                    @elseif ($item['route'] === 'pos.vendors')
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6h15m-13.5 4.5h12m-12 4.5h12m-12 4.5h9" /></svg>
                                    @elseif ($item['route'] === 'pos.purchases')
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9" /></svg>
                                    @elseif ($item['route'] === 'pos.customers')
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0 1 15 0" /></svg>
                                    @elseif ($item['route'] === 'pos.payments.index')
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75h-13.5" /></svg>
                                    @else
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-7.5 6h12m-9 6h6" /></svg>
                                    @endif
                                    {{ $item['label'] }}
                                </span>
                            </a>
                        @endforeach
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="rounded-md bg-rose-50 px-2 py-1 text-rose-600 transition hover:bg-rose-100 hover:text-rose-700">Logout</button>
                        </form>
                    </nav>
                    </div>

                    <nav id="mobile-menu" class="mt-3 hidden space-y-1 rounded-lg border border-sky-100 bg-white p-2 shadow-sm md:hidden" aria-label="Mobile menu">
                        @foreach ($menu as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                @class([
                                    'flex items-center gap-2 rounded-md px-3 py-2 text-sm transition',
                                    'bg-sky-50 text-sky-700' => request()->routeIs($item['route']),
                                    'text-slate-700 hover:bg-sky-50 hover:text-sky-700' => ! request()->routeIs($item['route']),
                                ])
                            >
                                @if ($item['route'] === 'dashboard')
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 9 8.25-6 8.25 6v10.5A1.5 1.5 0 0 1 18.75 21h-13.5a1.5 1.5 0 0 1-1.5-1.5V9Z" /></svg>
                                @elseif ($item['route'] === 'pos.sales')
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h12A2.25 2.25 0 0 1 20.25 6.75v10.5A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z" /></svg>
                                @elseif ($item['route'] === 'pos.products')
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h4.784c.597 0 1.169.237 1.591.659l1.216 1.216c.422.422.994.659 1.591.659H18a2.25 2.25 0 0 1 2.25 2.25v7.966A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z" /></svg>
                                @elseif ($item['route'] === 'pos.vendors')
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6h15m-13.5 4.5h12m-12 4.5h12m-12 4.5h9" /></svg>
                                @elseif ($item['route'] === 'pos.purchases')
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v15H3.75v-15Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9M7.5 13.5h9" /></svg>
                                @elseif ($item['route'] === 'pos.customers')
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0 1 15 0" /></svg>
                                @elseif ($item['route'] === 'pos.payments.index')
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75h-13.5" /></svg>
                                @else
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-7.5 6h12m-9 6h6" /></svg>
                                @endif
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mt-1 flex w-full items-center gap-2 rounded-md bg-rose-50 px-3 py-2 text-sm text-rose-700 transition hover:bg-rose-100">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12h9m0 0-3-3m3 3-3 3" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </header>

            <main class="mx-auto w-full max-w-screen-2xl flex-1 px-4 py-10 sm:px-6 lg:px-8">
                <div class="mb-5">
                    <button
                        type="button"
                        onclick="if(window.history.length > 1){ window.history.back(); } else { window.location.href='{{ route('dashboard') }}'; }"
                        class="inline-flex items-center gap-2 rounded-md bg-sky-50 px-3 py-2 text-sm font-medium text-sky-700 ring-1 ring-sky-100 transition hover:bg-sky-100"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                        Back
                    </button>
                </div>
                @yield('page-content')
            </main>

            <footer class="mt-auto border-t border-sky-100 bg-white/80 backdrop-blur-sm">
                <div class="mx-auto flex max-w-screen-2xl flex-wrap items-center justify-between gap-3 px-4 py-3 text-xs text-slate-500 sm:px-6 lg:px-8">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                    <p> Design and Develop BY Wise Dynamic IT</p>
                </div>
            </footer>
        </div>
    </body>
</html>
