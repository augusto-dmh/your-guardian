<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\Amount;
use App\QueryOptions\Sort\DueDate;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Bill\BillShowRequest;
use App\Http\Requests\Bill\BillStoreRequest;
use App\Http\Requests\Bill\BillDeleteRequest;
use App\Http\Requests\Bill\BillUpdateRequest;

/**
 * @see \App\Observers\BillObserver
 */
class BillController extends Controller
{
    public function store(BillStoreRequest $request)
    {
        $bill = Auth::user()->bills()->create($request->validated());

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $query = Auth::user()->bills()->getQuery();

        $query = Pipeline::send($query)
            ->through([DueDate::class, Amount::class, Status::class])
            ->thenReturn();

        $searchTerm = $request->input('searchTerm');
        $query
            ->where('title', 'like', '%' . $searchTerm . '%')
            ->orWhere('description', 'like', '%' . $searchTerm . '%')
            ->orderByRaw(
                "
                CASE
                    WHEN title LIKE ? THEN 1
                    WHEN description LIKE ? THEN 2
                    ELSE 3
                END
            ",
                ["%$searchTerm%", "%$searchTerm%"]
            );

        $bills = $query->paginate(10);

        return view('bills.index', compact('bills', 'searchTerm'));
    }

    public function show(BillShowRequest $request, Bill $bill)
    {
        return view('bills.show', compact('bill'));
    }

    public function update(BillUpdateRequest $request, Bill $bill)
    {
        $originalBillStatus = $bill->status;

        $bill->update($request->validated());

        if (
            $request->validated('status') === 'paid' &&
            $originalBillStatus !== 'paid'
        ) {
            $request
                ->session()
                ->flash(
                    'success',
                    __("Bill status changed to 'paid' successfully!")
                );
        }
        return redirect()->back();
    }

    public function destroy(BillDeleteRequest $request, Bill $bill)
    {
        $bill->delete();

        if (preg_match('/\/bills\/\d+$/', URL::previous())) {
            return redirect()->route('bills.index');
        }
        return redirect()->back();
    }

    public function create()
    {
        return view('bills.create');
    }

    public function edit(Bill $bill)
    {
        return view('bills.edit', compact('bill'));
    }
}
