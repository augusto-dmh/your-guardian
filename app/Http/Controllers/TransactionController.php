<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(TransactionRequest $request)
    {
        $user = Auth::user();

        $validatedData = $request->validated();

        $type = $request->type;
        $amount = $request->amount;

        $type === 'expense' && ($amount = -$amount);

        $user->transactions()->create($validatedData);

        return redirect()->back();
    }

    public function index()
    {
        return view('transactions.index');
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function update(
        TransactionRequest $request,
        Transaction $transaction
    ) {
        $validatedData = $request->validated();

        $amount = $request->amount;
        $type = $request->type;

        $amount =
            ($type === 'expense' && $amount > 0) ||
            ($type === 'income' && $amount < 0)
                ? -$amount
                : $amount;

        $transaction->update($validatedData);

        return redirect()->back();
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->back();
    }

    public function create()
    {
        return view('transactions.create', [
            'user' => Auth::user(),
            'transactionCategories' => TransactionCategory::all(),
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'transactionCategories' => TransactionCategory::all(),
        ]);
    }
}
