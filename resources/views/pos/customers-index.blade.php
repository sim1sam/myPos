@extends('layouts.pos-app')

@section('title', 'All Customers — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">All Customers</h1>
            <div class="flex w-full max-w-3xl items-center justify-end gap-2">
                <form method="GET" action="{{ route('pos.customers.index') }}" class="flex w-full max-w-md items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by ID, name, city, email, mobile..."
                        class="pos-input py-2.5"
                    >
                    <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Search</button>
                    @if (request('search'))
                        <a href="{{ route('pos.customers.index') }}" class="pos-btn-ghost py-2.5">Clear</a>
                    @endif
                </form>
                <a href="{{ route('pos.customers.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create</a>
            </div>
        </div>
        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">ID</th>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">Address</th>
                            <th class="px-4 py-3 font-semibold">City</th>
                            <th class="px-4 py-3 font-semibold">Pin</th>
                            <th class="px-4 py-3 font-semibold">State</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Mobile</th>
                            <th class="px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="px-4 py-3 font-medium text-sky-700">{{ $customer->customer_code ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $customer->name }}</td>
                                <td class="px-4 py-3">{{ $customer->address ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $customer->city ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $customer->pin_code ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $customer->state ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $customer->email ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $customer->mobile ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('pos.customers.edit', $customer) }}" class="rounded-md bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 ring-1 ring-sky-100 hover:bg-sky-100">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('pos.customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 ring-1 ring-rose-100 hover:bg-rose-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-slate-500">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $customers->links() }}
        </div>
    </section>
@endsection
