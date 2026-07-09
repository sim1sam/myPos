@extends('layouts.pos-app')

@section('title', 'Terminal — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Terminal</h1>
                <p class="mt-1 text-sm text-slate-500">Run common post-deployment commands on the live server.</p>
            </div>
            <a href="{{ route('pos.settings') }}" class="pos-btn-ghost">Back to Settings</a>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-3">
            <div class="pos-dashboard-card flex min-h-[28rem] flex-col lg:col-span-2">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-800">Command Status</h2>
                    <span id="terminal-status" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Idle</span>
                </div>

                <pre id="terminal-output" class="mt-4 flex-1 overflow-auto rounded-lg bg-slate-900 p-4 text-xs leading-6 text-emerald-300">Ready. Select a command from the right panel.</pre>
            </div>

            <div class="pos-dashboard-card">
                <h2 class="text-lg font-semibold text-slate-800">Commands</h2>
                <p class="mt-2 text-sm text-slate-500">These actions run directly on this server using Laravel Artisan.</p>

                <div class="mt-5 space-y-3">
                    <button
                        type="button"
                        class="terminal-command terminal-cmd-btn terminal-cmd-migrate"
                        data-action="migrate"
                        data-label="Run Migration"
                    >
                        <span class="terminal-cmd-icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/>
                            </svg>
                        </span>
                        <span>
                            <span class="terminal-cmd-title">Run Migration</span>
                            <span class="terminal-cmd-desc">Apply pending database migrations with --force</span>
                        </span>
                    </button>

                    <button
                        type="button"
                        class="terminal-command terminal-cmd-btn terminal-cmd-cache"
                        data-action="clear-cache"
                        data-label="Clear Cache"
                    >
                        <span class="terminal-cmd-icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/>
                            </svg>
                        </span>
                        <span>
                            <span class="terminal-cmd-title">Clear Cache</span>
                            <span class="terminal-cmd-desc">Flush config, route, view, and application caches</span>
                        </span>
                    </button>

                    <button
                        type="button"
                        class="terminal-command terminal-cmd-btn terminal-cmd-deploy"
                        data-action="post-deployment"
                        data-label="Post Deployment"
                    >
                        <span class="terminal-cmd-icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.83m5.84-2.55a6 6 0 0 0-7.38-5.84h4.83m2.55 5.84a6 6 0 0 1-7.38 5.84v-4.83m7.38-5.84h-4.83m-2.55-5.84a6 6 0 0 0 5.84 7.38v4.83"/>
                            </svg>
                        </span>
                        <span>
                            <span class="terminal-cmd-title">Post Deployment</span>
                            <span class="terminal-cmd-desc">Rebuild caches, remove hot file, and link storage</span>
                        </span>
                    </button>
                </div>

                <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                    <p class="font-semibold">Post Deployment runs:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-4">
                        <li>Remove <code class="rounded bg-amber-100 px-1">public/hot</code></li>
                        <li><code class="rounded bg-amber-100 px-1">php artisan optimize:clear</code></li>
                        <li><code class="rounded bg-amber-100 px-1">php artisan config:cache</code></li>
                        <li><code class="rounded bg-amber-100 px-1">php artisan route:cache</code></li>
                        <li><code class="rounded bg-amber-100 px-1">php artisan view:cache</code></li>
                        <li><code class="rounded bg-amber-100 px-1">php artisan storage:link</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const output = document.getElementById('terminal-output');
            const status = document.getElementById('terminal-status');
            const buttons = document.querySelectorAll('.terminal-command');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
            let running = false;

            const statusClasses = {
                idle: 'rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600',
                running: 'rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700',
                success: 'rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700',
                failed: 'rounded-full bg-rose-100 px-3 py-1 text-xs font-medium text-rose-700',
            };

            const setStatus = (label, type) => {
                status.textContent = label;
                status.className = statusClasses[type] ?? statusClasses.idle;
            };

            const appendOutput = (text) => {
                output.textContent = output.textContent.trim() === 'Ready. Select a command from the right panel.'
                    ? text
                    : `${output.textContent}\n\n${text}`;
                output.scrollTop = output.scrollHeight;
            };

            const setButtonsDisabled = (disabled) => {
                buttons.forEach((button) => {
                    button.disabled = disabled;
                    button.classList.toggle('opacity-60', disabled);
                    button.classList.toggle('cursor-not-allowed', disabled);
                });
            };

            buttons.forEach((button) => {
                button.addEventListener('click', async () => {
                    if (running) {
                        return;
                    }

                    const action = button.dataset.action;
                    const label = button.dataset.label ?? action;
                    running = true;
                    setButtonsDisabled(true);
                    setStatus(`Running: ${label}`, 'running');

                    const startedAt = new Date().toLocaleString();
                    appendOutput(`[${startedAt}] ${label} started...`);

                    try {
                        const response = await fetch(@json(route('pos.settings.terminal.run')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({ action }),
                        });

                        const payload = await response.json();
                        const finishedAt = new Date().toLocaleString();

                        appendOutput(`[${finishedAt}] ${label} ${payload.ok ? 'completed successfully' : 'failed'}\n${payload.output ?? 'No output returned.'}`);
                        setStatus(payload.ok ? 'Success' : 'Failed', payload.ok ? 'success' : 'failed');
                    } catch (error) {
                        const finishedAt = new Date().toLocaleString();
                        appendOutput(`[${finishedAt}] ${label} failed\n${error.message}`);
                        setStatus('Failed', 'failed');
                    } finally {
                        running = false;
                        setButtonsDisabled(false);
                    }
                });
            });
        });
    </script>
@endsection
