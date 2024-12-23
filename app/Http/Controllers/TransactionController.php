<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\Date;
use App\QueryOptions\Filter\Type;
use App\QueryOptions\Sort\Amount;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Pipeline;
use App\Services\TransactionFieldService;
use App\Http\Requests\Transaction\TransactionShowRequest;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Requests\Transaction\TransactionDeleteRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;

class TransactionController extends Controller
{
    public $transactionFieldService;

    public function __construct(TransactionFieldService $transactionFieldService)
    {
        $this->transactionFieldService = $transactionFieldService;
    }

    public function store(TransactionStoreRequest $request)
    {
        Auth::user()->transactions()->create($request->validated());

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $sortFields = $this->transactionFieldService->getSortFields();
        $searchTerm = $request->input('searchTerm');

        $query = Auth::user()->transactions()->getQuery();

        $query = Pipeline::send($query)
            ->through([Amount::class, Date::class, Type::class])
            ->thenReturn();

        $query->when($searchTerm, function ($query, $searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query
                    ->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });

            $query->orderByRaw(
                "
                        CASE
                            WHEN title LIKE ? AND description LIKE ? THEN 1
                            WHEN title LIKE ? THEN 2
                            WHEN description LIKE ? THEN 3
                            ELSE 4
                        END
                        ",
                [
                    "%$searchTerm%",
                    "%$searchTerm%",
                    "%$searchTerm%",
                    "%$searchTerm%",
                ]
            );
        });

        $transactions = $query->paginate(10);

        $filterFields = $this->transactionFieldService->getFilterFields();

        return view(
            'transactions.index',
            compact('transactions', 'searchTerm', 'sortFields', 'filterFields')
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
            'transactionTypes' => EnumHelper::getEnumValues('transactions', 'type'),
            'user' => Auth::user(),
            'transactionCategories' => TransactionCategory::all(),
        ]);
    }

    public function edit(Transaction $transaction)
    {
        $textFields = $this->transactionFieldService->getTextFields($transaction);
        $selectFields = $this->transactionFieldService->getSelectFields($transaction);

        return view('transactions.edit', [
            'transaction' => $transaction,
            'textFields' => $textFields,
            'selectFields' => $selectFields,
        ]);
    }
}
