@extends('layouts.pos-app')

@section('title', 'Role System — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Role System</h1>
            <a href="{{ route('pos.settings') }}" class="pos-btn-ghost">Back</a>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 pos-dashboard-card">
            <h2 class="text-lg font-semibold text-slate-800">Create Role</h2>
            <form method="POST" action="{{ route('pos.settings.roles.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="pos-label" for="name">Role Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="pos-input @error('name') pos-input-error @enderror" placeholder="Enter role name" required>
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="pos-label">Function Select (User-wise)</label>
                    <div class="space-y-4">
                        @foreach ($permissionSections as $section => $keys)
                            <div class="rounded-lg border border-sky-100 bg-sky-50/40 p-3">
                                <h3 class="text-sm font-semibold text-sky-800">{{ $section }}</h3>
                                <div class="mt-2 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($keys as $key)
                                        <label class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                                            <input type="checkbox" name="permissions[]" value="{{ $key }}" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                            <span>{{ $functionOptions[$key] ?? $key }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Create Role</button>
                </div>
            </form>
        </div>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">SL#</th>
                            <th class="px-4 py-3 font-semibold">Role Name</th>
                            <th class="px-4 py-3 font-semibold">Functions</th>
                            <th class="px-4 py-3 font-semibold">Created On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($roles as $role)
                            <tr>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $role->name }}</td>
                                <td class="px-4 py-3">
                                    {{ collect($role->permissions ?? [])->map(fn ($p) => $functionOptions[$p] ?? $p)->implode(', ') ?: '-' }}
                                </td>
                                <td class="px-4 py-3">{{ optional($role->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $roles->links() }}
        </div>
    </section>
@endsection
