<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\Amount;
use App\Http\Requests\BillRequest;
use App\QueryOptions\Sort\DueDate;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Pipeline;

class BillController extends Controller
{
    public function store(BillRequest $request)
    {
        $bill = Auth::user()->bills()->create($request->validated());

        if ($bill->due_date->isFuture() && $bill->status == 'pending') {
            $nextPendingBillDueDate = Cache::get(
                "user_{$bill->user_id}_next_bill_due"
            );

            if (
                !$nextPendingBillDueDate ||
                $bill->due_date->format('Y-m-d') < $nextPendingBillDueDate
            ) {
                Cache::put(
                    "user_{$bill->user_id}_next_bill_due",
                    $bill->due_date->format('Y-m-d'),
                    60
                );
            }
        }

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

        if ($bill->due_date->isFuture() && $bill->status == 'pending') {
            $nextPendingBillDueDate =
                Cache::get("user_{$bill->user_id}_next_bill_due") ??
                ($bill->user
                    ->bills()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()
                    ?->due_date->format('Y-m-d') ??
                    'none');

            $bill->due_date->format('Y-m-d') < $nextPendingBillDueDate
                ? Cache::put(
                    "user_{$bill->user_id}_next_bill_due",
                    $bill->due_date->format('Y-m-d'),
                    60
                )
                : Cache::add(
                    "user_{$bill->user_id}_next_bill_due",
                    $nextPendingBillDueDate,
                    60
                );
        }

        return redirect()->back();
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();

        if (
            $bill->due_date->isFuture() &&
            $bill->status == 'pending' &&
            Cache::get("user_{$bill->user_id}_next_bill_due") ==
                $bill->due_date->format('Y-m-d')
        ) {
            Cache::put(
                "user_{$bill->user_id}_next_bill_due",
                $bill->user
                    ->bills()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()
                    ->due_date?->format('Y-m-d') ?? 'none',
                60
            );
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
