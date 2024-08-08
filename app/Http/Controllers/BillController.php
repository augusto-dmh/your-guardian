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
        $filterByStatus = $request->input('filterByStatus') ?? [];
        $sortByAmount = $request->input('sortByAmount');
        $sortByDueDate = $request->input('sortByDueDate');

        $query = Auth::user()->bills()->getQuery();

        $bills = Pipeline::send($query)
            ->through([
                function ($query, $next) use ($filterByStatus) {
                    return (new Status($filterByStatus))->handle($query, $next);
                },
                function ($query, $next) use ($sortByAmount) {
                    return (new Amount($sortByAmount))->handle($query, $next);
                },
                function ($query, $next) use ($sortByDueDate) {
                    return (new DueDate($sortByDueDate))->handle($query, $next);
                },
            ])
            ->thenReturn()
            ->paginate(10);

        return view(
            'bills.index',
            compact('bills', 'filterByStatus', 'sortByAmount', 'sortByDueDate')
        );
    }

    public function show(BillShowRequest $request, Bill $bill)
    {
        return view('bills.show', compact('bill'));
    }

    public function update(BillUpdateRequest $request, Bill $bill)
    {
        $bill->update($request->validated());

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
