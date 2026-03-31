@extends('layouts.pos-app')

@section('title', 'Expense List — ' . config('app.name'))

@section('page-content')
    <section>
        <div class="flex items-end justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-800">Expense List</h1>
            <a href="{{ route('pos.expenses.create') }}" class="pos-btn-primary w-auto! px-5 py-2.5">Create Expense</a>
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
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Expense Head</th>
                            <th class="px-4 py-3 font-semibold">Payment Mode</th>
                            <th class="px-4 py-3 font-semibold">Amount</th>
                            <th class="px-4 py-3 font-semibold">Remarks</th>
                            <th class="px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($expenses as $expense)
                            <tr>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ ($expenses->currentPage() - 1) * $expenses->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-3">{{ optional($expense->expense_date)->format('d-m-Y') }}</td>
                                <td class="px-4 py-3">{{ $expense->expenseHead?->name ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $expense->paymentMode?->name ?: '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-sky-700">Rs {{ number_format((float) $expense->amount, 2) }}</td>
                                <td class="px-4 py-3">{{ $expense->remarks ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('pos.expenses.edit', $expense) }}" class="pos-btn-ghost py-1.5 text-xs">Edit</a>
                                        <form method="POST" action="{{ route('pos.expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">No expenses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pos-pagination mt-4">
            {{ $expenses->links() }}
        </div>
    </section>
@endsection
