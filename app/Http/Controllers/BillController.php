<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bill\BillStoreRequest;
use App\Http\Requests\Bill\BillUpdateRequest;
use Auth;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\Amount;
use App\QueryOptions\Sort\DueDate;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\Pipeline;

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

        $bills = Pipeline::send($query)
            ->through([DueDate::class, Amount::class, Status::class])
            ->thenReturn()
            ->paginate(10);

        return view('bills.index', compact('bills'));
    }

    public function show(Bill $bill)
    {
        return view('bills.show', compact('bill'));
    }

    public function update(BillUpdateRequest $request, Bill $bill)
    {
        $bill->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Bill $bill)
    {
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
