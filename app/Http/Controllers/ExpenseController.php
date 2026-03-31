<?php

namespace App\Http\Controllers;

use App\Models\ExpenseHead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function dashboard(): View
    {
        return view('pos.expenses');
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
