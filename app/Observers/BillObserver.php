<?php

namespace App\Observers;

use App\Models\Bill;
use Illuminate\Support\Facades\Cache;

class BillObserver
{
    public function created(Bill $bill): void
    {
        if ($bill->due_date->isFuture() && $bill->status == 'pending') {
            $nextPendingBillDueDate = Cache::get(
                "user_{$bill->user_id}_next_bill_due"
            );

            if (
                !$nextPendingBillDueDate ||
                $bill->due_date < $nextPendingBillDueDate
            ) {
                Cache::put(
                    "user_{$bill->user_id}_next_bill_due",
                    $bill->due_date->format('Y-m-d'),
                    60
                );
            }
        }
    }

    public function updated(Bill $bill): void
    {
        $bill->refresh();

        if ($bill->due_date->isFuture() && $bill->status == 'pending') {
            $nextPendingBillDueDate =
                Cache::get("user_{$bill->user_id}_next_bill_due") ??
                $bill->user
                    ->bills()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()?->due_date->format('Y-m-d');

            $bill->due_date <= $nextPendingBillDueDate
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
    }
    public function deleted(Bill $bill): void
    {
        if (
            $bill->due_date->isFuture() &&
            $bill->status == 'pending' &&
            Cache::get("user_{$bill->user_id}_next_bill_due") == $bill->due_date->format('Y-m-d')
        ) {
            $nextPendingBillDueDate = $bill->user
                ->bills()
                ->where('due_date', '>=', now())
                ->where('status', '=', 'pending')
                ->orderBy('due_date', 'asc')
                ->first()?->due_date->format('Y-m-d');

            if ($nextPendingBillDueDate !== null) {
                Cache::put(
                    "user_{$bill->user_id}_next_bill_due",
                    $nextPendingBillDueDate,
                    60
                );
            } else {
                Cache::forget("user_{$bill->user_id}_next_bill_due");
            }
        }
    }

    public function creating(Bill $bill): void
    {
        if ($bill->status === 'paid') {
            $bill->paid_at = now();
        }
    }

    public function updating(Bill $bill): void
    {
        if (
            $bill->isDirty('status') &&
            $bill->status === 'paid' &&
            $bill->getOriginal('status') !== 'paid'
        ) {
            $bill->paid_at = now();
        }
    }
}
