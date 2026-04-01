@extends('layouts.pos-app')

@section('title', 'Edit Role — ' . config('app.name'))

@section('page-content')
    <section>
        @php
            $selectedPermissions = collect(old('permissions', $rolePermissions ?? $role->permissions ?? []))
                ->map(fn ($permission) => (string) $permission)
                ->values()
                ->all();
        @endphp
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Edit Role</h1>
            <a href="{{ route('pos.settings.roles') }}" class="pos-btn-ghost">Back</a>
        </div>

        <div class="mt-6 pos-dashboard-card">
            <form method="POST" action="{{ route('pos.settings.roles.update', $role) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="pos-label" for="name">Role Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $role->name) }}" class="pos-input @error('name') pos-input-error @enderror" required>
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
                                            <input
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $key }}"
                                                class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                                                @checked(in_array($key, $selectedPermissions, true))
                                            >
                                            <span>{{ $functionOptions[$key] ?? $key }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Update Role</button>
                </div>
            </form>
        </div>
    </section>
@endsection
