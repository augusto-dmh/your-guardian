<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\Amount;
use App\QueryOptions\Sort\DueDate;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Bill\BillStoreRequest;
use App\Http\Requests\Bill\BillUpdateRequest;

class BillController extends Controller
{
    public function store(BillStoreRequest $request)
    {
        if ($request['status'] === 'paid') {
            $request->validated()->paid_at = now();
        }

        $bill = Auth::user()->bills()->create($request->validated());

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $query = Auth::user()->bills()->getQuery();

        $bills = Pipeline::send($query)
            ->through([DueDate::class, Amount::class, Status::class])
            ->thenReturn()
            ->paginate(10);

        return view('bills.index', compact('bills'));
    }

    public function show(Bill $bill)
    {
        Gate::authorize('view', $bill);

        return view('bills.show', compact('bill'));
    }

    public function update(BillUpdateRequest $request, Bill $bill)
    {
        Gate::authorize('update', $bill);

        if ($request['status'] === 'paid' && $bill->status !== 'paid') {
            $bill->paid_at = now();
        }

        $bill->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Bill $bill)
    {
        Gate::authorize('delete', $bill);

        $bill->delete();

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
