@extends('layouts.pos-app')

@section('title', 'Expense Head — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Expense Head</h1>
            <a href="{{ route('pos.expenses') }}" class="pos-btn-ghost">Back</a>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 pos-dashboard-card">
            <h2 class="text-lg font-semibold text-slate-800">Create Expense Head</h2>
            <form method="POST" action="{{ route('pos.expenses.heads.store') }}" class="mt-4 grid gap-4 md:grid-cols-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="pos-label" for="name">Head Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter head name" class="pos-input @error('name') pos-input-error @enderror" required>
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-end">
                    <button type="submit" class="pos-btn-primary w-auto! px-6">Create</button>
                </div>
            </form>
        </div>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">SL#</th>
                            <th class="px-4 py-3 font-semibold">Head Name</th>
                            <th class="px-4 py-3 font-semibold">Created On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($heads as $head)
                            <tr>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ ($heads->currentPage() - 1) * $heads->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $head->name }}</td>
                                <td class="px-4 py-3">{{ optional($head->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-slate-500">No expense head found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $heads->links() }}
        </div>
    </section>
@endsection
