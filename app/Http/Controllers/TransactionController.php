<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store()
    {
        $user = Auth::user();

        $amount = request('amount');
        $type = request('type');
        $category = request('category');
        $description = request('description');

        $type === 'expense' && ($amount = -$amount);

        $user->transactions()->create([
            'amount' => $amount,
            'type' => $type,
            'transaction_category_id' => TransactionCategory::where([
                'name' => $category,
            ])->first()->id,
            'description' => $description,
        ]);

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

    public function update(Transaction $transaction)
    {
        $amount = request('amount');
        $type = request('type');
        $category = request('category');
        $description = request('description');

        $amount =
            ($type === 'expense' && $amount > 0) ||
            ($type === 'income' && $amount < 0)
                ? -$amount
                : $amount;

        $transaction->update([
            'amount' => $amount,
            'type' => $type,
            'transaction_category_id' => TransactionCategory::where([
                'name' => $category,
            ])->first()->id,
            'description' => $description,
        ]);

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
