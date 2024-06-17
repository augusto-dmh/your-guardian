<?php

namespace App\Listeners;

use App\Events\BillCreated;
use App\Events\BillDeleted;
use App\Events\BillUpdated;
use App\Events\EventInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleBillCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public static function handle(BillCreated|BillUpdated|BillDeleted $event)
    {
        if ($event instanceof BillCreated) {
            self::handleCreatedBill($event);
        } elseif ($event instanceof BillUpdated) {
            self::handleUpdatedBill($event);
        } elseif ($event instanceof BillDeleted) {
            self::handleDeletedBill($event);
        }
    }

    private static function handleCreatedBill(BillCreated $event)
    {
        $bill = $event->getEntity();

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

    private static function handleUpdatedBill(BillUpdated $event)
    {
        $bill = $event->getEntity();
        $bill->refresh();

        if ($bill->due_date->isFuture() && $bill->status == 'pending') {
            $nextPendingBillDueDate =
                Cache::get("user_{$bill->user_id}_next_bill_due") ??
                $bill->user
                    ->bills()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()?->due_date;

            $bill->due_date <= $nextPendingBillDueDate
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

    private static function handleDeletedBill(BillDeleted $event)
    {
        $bill = $event->getEntity();

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
