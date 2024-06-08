<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\Amount;
use App\Http\Requests\BillRequest;
use App\QueryOptions\Filter\Status;
use App\QueryOptions\Sort\DueDate;
use Illuminate\Support\Facades\Pipeline;

class BillController extends Controller
{
    public function store(BillRequest $request)
    {
        Auth::user()->bills()->create($request->validated());

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

    public function update(BillRequest $request, Bill $bill)
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
