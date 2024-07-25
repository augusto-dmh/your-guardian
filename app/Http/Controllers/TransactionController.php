<?php

namespace App\Http\Controllers;

use Request;
use App\Models\Transaction;
use App\QueryOptions\Sort\Date;
use App\QueryOptions\Filter\Type;
use App\QueryOptions\Sort\Amount;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;

class TransactionController extends Controller
{
    public function store(TransactionStoreRequest $request)
    {
        $user = Auth::user();

        $isTransactionValid = TransactionCategory::query()
            ->where('id', '=', $request['transaction_category_id'])
            ->where('transaction_type', '=', $request['type'])
            ->exists();

        if (!$isTransactionValid) {
            return redirect()
                ->back()
                ->withErrors([
                    'transaction_category' =>
                        'Invalid transaction category or type.',
                ]);
        }

        $validatedData = $request->validated();

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->getQuery();

        $transactions = Pipeline::send($query)
            ->through([Amount::class, Date::class, Type::class])
            ->thenReturn()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        Gate::authorize('view', $transaction);

        return view('transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function update(
        TransactionUpdateRequest $request,
        Transaction $transaction
    ) {
        Gate::authorize('update', $transaction);

        $isTransactionValid = TransactionCategory::query()
            ->where('id', '=', $request['transaction_category_id'])
            ->where('transaction_type', '=', $request['type'])
            ->exists();

        if (!$isTransactionValid) {
            return redirect()
                ->back()
                ->withErrors([
                    'transaction_category' =>
                        'Invalid transaction category or type.',
                ]);
        }

        $validatedData = $request->validated();

        $transaction->update($validatedData);

        return redirect()->back();
    }

    public function destroy(Transaction $transaction)
    {
        Gate::authorize('delete', $transaction);

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
