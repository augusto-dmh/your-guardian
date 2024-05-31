<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Auth;
use Illuminate\Http\Request;

class BillController extends Controller
{
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

    public function index()
    {
        $bills = Auth::user()->bills()->get();

        return view('bills.index', compact('bills'));
    }

    public function show(Bill $bill)
    {
        return view('bills.show', compact('bill'));
    }

    public function update(Request $request, Bill $bill)
    {
        $bill->update([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

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
