@extends('layouts.pos-app')

@section('title', 'List Vendors — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Vendor List</h1>
            <div class="flex w-full max-w-3xl items-center justify-end gap-2">
                <form method="GET" action="{{ route('pos.vendors.index') }}" class="flex w-full max-w-md items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by ID, name, GSTIN, mobile, bank..."
                        class="pos-input py-2.5"
                    >
                    <button type="submit" class="pos-btn-primary w-auto! px-5 py-2.5">Search</button>
                    @if (request('search'))
                        <a href="{{ route('pos.vendors.index') }}" class="pos-btn-ghost py-2.5">Clear</a>
                    @endif
                </form>
                <a href="{{ route('pos.vendors.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create Vendor</a>
            </div>
        </div>

        <div class="mt-6 pos-dashboard-card p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">ID</th>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">GSTIN</th>
                            <th class="px-4 py-3 font-semibold">Mobile</th>
                            <th class="px-4 py-3 font-semibold">Account Name</th>
                            <th class="px-4 py-3 font-semibold">A/C No</th>
                            <th class="px-4 py-3 font-semibold">IFSC</th>
                            <th class="px-4 py-3 font-semibold">Bank</th>
                            <th class="px-4 py-3 font-semibold">Branch</th>
                            <th class="px-4 py-3 font-semibold">GPay/PhonePe</th>
                            <th class="px-4 py-3 font-semibold">Document</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($vendors as $vendor)
                            <tr>
                                <td class="px-4 py-3 font-medium text-sky-700">{{ $vendor->vendor_code ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->name }}</td>
                                <td class="px-4 py-3">{{ $vendor->gstin ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->mobile_no ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->account_name ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->account_no ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->ifsc ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->bank_name ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->branch_name ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $vendor->gpay_phonepay_no ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($vendor->document_path)
                                        <a href="{{ asset('storage/' . $vendor->document_path) }}" target="_blank" class="text-sky-700 hover:underline">View</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-8 text-center text-slate-500">No vendors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $vendors->links() }}
        </div>
    </section>
@endsection
