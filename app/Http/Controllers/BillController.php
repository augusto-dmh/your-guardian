<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Auth;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function edit(Bill $bill)
    {
        return view('bills.edit', $bill);
    }

    public function create()
    {
        return view('bills.create');
    }

    public function index()
    {
        $bills = Auth::user()->bills()->get();

        return view('bills.index', $bills);
    }

    public function store(Request $request)
    {
        Auth::user()
            ->bills()
            ->create([
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
            ]);

        return redirect()->back();
    }

    public function update(Request $request, Bill $bill)
    {
        $bill->update([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back();
    }

    public function show(Bill $bill)
    {
        return view('bills.show', $bill);
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();

        return redirect()->back();
    }
}
