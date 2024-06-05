<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillRequest;
use App\Models\Bill;
use Auth;

class BillController extends Controller
{
    public function store(BillRequest $request)
    {
        Auth::user()->bills()->create($request->validated());

        return redirect()->back();
    }

    public function index()
    {
        $bills = Auth::user()->bills()->get();

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
