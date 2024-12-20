<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
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
        $sortFields = ['Amount', 'Due Date'];
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

        $transactionTypes = EnumHelper::getEnumValues('transactions', 'type');

        $filterFields = [
            ['name' => 'Type', 'values' => $transactionTypes],
        ];

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
        $textFields = [
            ['name' => 'amount', 'exhibitionName' => 'Amount', 'value' => $transaction->amount],
        ];

        $selectFields = [
            [
                'name' => 'type',
                'exhibitionName' => 'Type',
                'value' => $transaction->type,
                'options' => array_map(function ($type) {
                    return ['value' => $type, 'label' => $type];
                }, EnumHelper::getEnumValues('transactions', 'type'))
            ],
            [
                'name' => 'transaction_category_id',
                'exhibitionName' => 'Category',
                'value' => $transaction->transactionCategory?->id,
                'options' => TransactionCategory::all()->map(function ($category) {
                    return ['value' => $category->id, 'label' => $category->name];
                })->toArray()
            ]
        ];

        return view('transactions.edit', [
            'transaction' => $transaction,
            'textFields' => $textFields,
            'selectFields' => $selectFields,
        ]);
    }
}
