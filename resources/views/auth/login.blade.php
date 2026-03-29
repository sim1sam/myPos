@extends('layouts.pos-guest')

@section('title', 'Sign in — ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-slate-950">
        <div class="grid min-h-screen auto-rows-fr lg:grid-cols-[1fr_minmax(0,34rem)] xl:grid-cols-[1.05fr_minmax(0,38rem)]">
            {{-- Brand panel (desktop) --}}
            <aside
                class="relative hidden min-h-screen overflow-hidden lg:flex lg:min-h-screen lg:flex-col lg:justify-between lg:bg-gradient-to-br lg:from-slate-950 lg:via-blue-950 lg:to-slate-900 lg:px-12 lg:py-14 xl:px-16"
                aria-hidden="true"
            >
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_90%_60%_at_20%_0%,rgba(56,189,248,0.22),transparent)]"></div>
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(37,99,235,0.18),transparent)]"></div>
                <div
                    class="pointer-events-none absolute inset-0 opacity-[0.14]"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=\'72\' height=\'72\' viewBox=\'0 0 72 72\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath fill=\'%23ffffff\' fill-opacity=\'0.4\' d=\'M0 0h36v36H0V0zm36 36h36v36H36V36z\'/%3E%3C/svg%3E');"
                ></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-500/15 ring-1 ring-sky-400/35">
                            <svg class="h-7 w-7 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3H15m7 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-lg font-semibold tracking-tight text-white">{{ config('app.name') }}</p>
                            <p class="text-sm text-sky-200/75">Retail · checkout · inventory</p>
                        </div>
                    </div>
                    <h2 class="mt-14 max-w-md text-3xl font-semibold leading-tight tracking-tight text-white xl:text-4xl">
                        Fast checkout.<br />
                        <span class="text-sky-300">Clear numbers.</span>
                    </h2>
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-sky-100/70">
                        Sign in to open your register, manage sales, and keep the queue moving.
                    </p>
                </div>

                <ul class="relative z-10 mt-12 grid max-w-md gap-4 text-sm text-sky-100/80" role="list">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-sky-500/20 text-sky-200">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </span>
                        <span>Role-ready sessions with secure sign-out.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-sky-500/20 text-sky-200">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </span>
                        <span>Built with Laravel, Tailwind, and a blue-first UI.</span>
                    </li>
                </ul>
            </aside>

            {{-- Form column --}}
            <div class="relative flex min-h-screen flex-col justify-center bg-gradient-to-b from-slate-950 via-blue-950 to-slate-950 px-5 py-14 sm:px-10 sm:py-16 lg:min-h-screen lg:bg-gradient-to-b lg:from-slate-950 lg:via-slate-900 lg:to-slate-950 lg:px-14 lg:py-16 xl:px-20 xl:py-20 2xl:px-24">
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_70%_50%_at_50%_-10%,rgba(56,189,248,0.2),transparent)] lg:hidden"></div>

                <main class="relative z-10 mx-auto w-full max-w-lg lg:max-w-xl">
                    {{-- Mobile logo --}}
                    <header class="mb-10 text-center lg:hidden">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-500/20 ring-1 ring-sky-400/40">
                            <svg class="h-8 w-8 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3H15m7 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h1 class="text-xl font-semibold tracking-tight text-white">{{ config('app.name') }}</h1>
                        <p class="mt-1 text-sm text-sky-200/80">Sign in to your account</p>
                    </header>

                    <header class="mb-10 hidden lg:block lg:mb-12">
                        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white xl:text-3xl">Welcome back</h1>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 lg:text-base">Enter your credentials to open the register.</p>
                    </header>

                    <section class="pos-surface p-8 sm:p-10 lg:p-11 xl:p-12" aria-labelledby="login-heading">
                        <h2 id="login-heading" class="sr-only">Sign in</h2>
                        <form method="POST" action="{{ route('login') }}" class="space-y-7" novalidate>
                            @csrf

                            <div>
                                <label class="pos-label" for="email">Email address</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                        </svg>
                                    </span>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="username"
                                        autofocus
                                        placeholder="you@store.com"
                                        class="pos-input pl-11 @error('email') pos-input-error @enderror"
                                    />
                                </div>
                                @error('email')
                                    <p class="mt-2 flex items-center gap-1.5 text-sm font-medium text-red-600 dark:text-red-400" role="alert">
                                        <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm1 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" clip-rule="evenodd" /></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="pos-label" for="password">Password</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        </svg>
                                    </span>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="••••••••"
                                        class="pos-input pl-11"
                                    />
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label class="group flex cursor-pointer items-center gap-2.5">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        class="size-4 rounded-md border-slate-300 text-sky-600 focus:ring-2 focus:ring-sky-500/30 dark:border-slate-600 dark:bg-slate-800"
                                    />
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Keep me signed in</span>
                                </label>
                            </div>

                            <button type="submit" class="pos-btn-primary">
                                <span>Sign in</span>
                                <svg class="h-4 w-4 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </form>
                    </section>

                    <footer class="mt-10 text-center lg:mt-12">
                        <p class="text-xs text-sky-200/55 lg:text-slate-500 dark:lg:text-slate-500">
                            &copy; {{ date('Y') }} {{ config('app.name') }} · Secure session
                        </p>
                    </footer>
                </main>
            </div>
        </div>
    </div>
@endsection
