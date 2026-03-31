@extends('layouts.pos-app')

@section('title', 'Payment Modes — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Payment Modes</h1>
            <a href="{{ route('pos.settings.payment-modes.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create</a>
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
                            <th class="px-4 py-3 font-semibold">SL#</th>
                            <th class="px-4 py-3 font-semibold">Payment Mode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($paymentModes as $mode)
                            <tr>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ ($paymentModes->currentPage() - 1) * $paymentModes->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $mode->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-8 text-center text-slate-500">No payment mode found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $paymentModes->links() }}
        </div>
    </section>
@endsection
