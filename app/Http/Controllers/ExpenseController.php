<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\PaymentMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function dashboard(): View
    {
        $totalExpenseAmount = (float) Expense::sum('amount');

        return view('pos.expenses', compact('totalExpenseAmount'));
    }

    public function create(): View
    {
        $expenseHeads = ExpenseHead::orderBy('name')->get(['id', 'name']);
        $paymentModes = PaymentMode::orderBy('name')->get(['id', 'name']);

        return view('pos.expenses-create', compact('expenseHeads', 'paymentModes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'expense_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_head_id' => ['required', 'exists:expense_heads,id'],
            'payment_mode_id' => ['required', 'exists:payment_modes,id'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        Expense::create($data);

        return redirect()->route('pos.expenses.create')->with('success', 'Expense created successfully.');
    }

    public function index(): View
    {
        $expenses = Expense::query()
            ->with(['expenseHead:id,name', 'paymentMode:id,name'])
            ->latest('expense_date')
            ->latest('id')
            ->paginate(15);

        return view('pos.expenses-index', compact('expenses'));
    }

    public function edit(Expense $expense): View
    {
        $expenseHeads = ExpenseHead::orderBy('name')->get(['id', 'name']);
        $paymentModes = PaymentMode::orderBy('name')->get(['id', 'name']);

        return view('pos.expenses-edit', compact('expense', 'expenseHeads', 'paymentModes'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $data = $request->validate([
            'expense_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_head_id' => ['required', 'exists:expense_heads,id'],
            'payment_mode_id' => ['required', 'exists:payment_modes,id'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $expense->update($data);

        return redirect()->route('pos.expenses.list')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('pos.expenses.list')->with('success', 'Expense deleted successfully.');
    }

    public function heads(): View
    {
        $heads = ExpenseHead::query()
            ->orderBy('name')
            ->paginate(15);

        return view('pos.expenses-heads', compact('heads'));
    }

    public function storeHead(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:expense_heads,name'],
        ]);

        ExpenseHead::create($data);

        return redirect()->route('pos.expenses.heads')->with('success', 'Expense head created successfully.');
    }
}
