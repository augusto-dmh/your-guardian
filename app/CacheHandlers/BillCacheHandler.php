<?php

namespace App\CacheHandlers;

use Illuminate\Support\Facades\Cache;

class BillCacheHandler
{
    public static function handleCreatedBill($bill)
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
                    $bill->due_date,
                    60
                );
            }
        }
    }

    public static function handleUpdatedBill($bill)
    {
        if ($bill->due_date->isFuture() && $bill->status == 'pending') {
            $nextPendingBillDueDate =
                Cache::get("user_{$bill->user_id}_next_bill_due") ??
                $bill->user
                    ->bills()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()?->due_date;

            $bill->due_date < $nextPendingBillDueDate
                ? Cache::put(
                    "user_{$bill->user_id}_next_bill_due",
                    $bill->due_date,
                    60
                )
                : Cache::add(
                    "user_{$bill->user_id}_next_bill_due",
                    $nextPendingBillDueDate,
                    60
                );
        }
    }

    public static function handleDeletedBill($bill)
    {
        if (
            $bill->due_date->isFuture() &&
            $bill->status == 'pending' &&
            Cache::get("user_{$bill->user_id}_next_bill_due") == $bill->due_date
        ) {
            Cache::put(
                "user_{$bill->user_id}_next_bill_due",
                $bill->user
                    ->bills()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()?->due_date,
                60
            );
        }
    }
}
