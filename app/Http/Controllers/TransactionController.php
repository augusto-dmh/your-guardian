<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\QueryOptions\Sort\Date;
use App\QueryOptions\Filter\Type;
use App\QueryOptions\Sort\Amount;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Transaction\TransactionShowRequest;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Requests\Transaction\TransactionDeleteRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(TransactionStoreRequest $request)
    {
        Auth::user()->transactions()->create($request->validated());

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $filterByType = $request->input('filterByType') ?? [];
        $sortByAmount = $request->input('sortByAmount');
        $sortByDate = $request->input('sortByDate');

        $query = Auth::user()->transactions()->getQuery();

        $transactions = Pipeline::send($query)
            ->through([
                function ($query, $next) use ($filterByType) {
                    return (new Type($filterByType))->handle($query, $next);
                },
                function ($query, $next) use ($sortByAmount) {
                    return (new Amount($sortByAmount))->handle($query, $next);
                },
                function ($query, $next) use ($sortByDate) {
                    return (new Date($sortByDate))->handle($query, $next);
                },
            ])
            ->thenReturn()
            ->paginate(10);

        return view(
            'transactions.index',
            compact(
                'transactions',
                'filterByType',
                'sortByAmount',
                'sortByDate'
            )
        );
    }

    public function show(
        TransactionShowRequest $request,
        Transaction $transaction
    ) {
        return view('transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function update(
        TransactionUpdateRequest $request,
        Transaction $transaction
    ) {
        $transaction->update($request->validated());

        return redirect()->back();
    }

    public function destroy(
        TransactionDeleteRequest $request,
        Transaction $transaction
    ) {
        $transaction->delete();

        if (preg_match('/\/transactions\/\d+$/', URL::previous())) {
            return redirect()->route('transactions.index');
        }
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
