@extends('layouts.pos-app')

@section('title', 'Edit User — ' . config('app.name'))

@section('page-content')
    <section class="mx-auto max-w-4xl">
        <div class="pos-dashboard-card">
            <div class="flex items-center justify-between gap-3">
                <h1 class="text-2xl font-semibold text-slate-800">Edit User</h1>
                <a href="{{ route('pos.settings.users') }}" class="pos-btn-ghost">Back</a>
            </div>

            <form method="POST" action="{{ route('pos.settings.users.update', $user) }}" class="mt-6 grid gap-4 md:grid-cols-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="pos-label" for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="pos-input @error('name') pos-input-error @enderror" required>
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="pos-label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="pos-input @error('email') pos-input-error @enderror" required>
                    @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="pos-label" for="password">Password (optional)</label>
                    <input id="password" name="password" type="password" class="pos-input @error('password') pos-input-error @enderror" placeholder="Leave blank to keep current">
                    @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Update User</button>
                </div>
            </form>
        </div>
    </section>
@endsection
