@extends('layouts.pos-app')

@section('title', 'Users — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Users</h1>
            <a href="{{ route('pos.settings') }}" class="pos-btn-ghost">Back</a>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->has('user'))
            <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first('user') }}
            </div>
        @endif

        <div class="mt-6 pos-dashboard-card">
            <h2 class="text-lg font-semibold text-slate-800">Create User</h2>
            <form method="POST" action="{{ route('pos.settings.users.store') }}" class="mt-4 grid gap-4 md:grid-cols-4">
                @csrf
                <div>
                    <label class="pos-label" for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="pos-input @error('name') pos-input-error @enderror" placeholder="Enter name" required>
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="pos-label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="pos-input @error('email') pos-input-error @enderror" placeholder="Enter email" required>
                    @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="pos-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="pos-input @error('password') pos-input-error @enderror" placeholder="Enter password" required>
                    @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Create User</button>
                </div>
            </form>
        </div>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">SL#</th>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Created On</th>
                            <th class="px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ optional($user->created_at)->format('d-m-Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('pos.settings.users.edit', $user) }}" class="pos-btn-ghost py-1.5 text-xs">Edit</a>
                                        @if (auth()->id() !== $user->id)
                                            <form method="POST" action="{{ route('pos.settings.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-100">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $users->links() }}
        </div>
    </section>
@endsection
