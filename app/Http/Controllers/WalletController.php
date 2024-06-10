<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WalletController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userId = $user->id;

        $balance = $user->balance;
        $full_name = $user->full_name;
        $lastTransaction = $user->transactions->last();
        $nextPendingBillDueDate = Cache::remember(
            "user_{$userId}_next_bill_due",
            60,
            function () use ($user) {
                $queriedNextPendingBillDueDate = $user
                    ->bills()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()?->due_date;

                return $queriedNextPendingBillDueDate;
            }
        );
        $nextPendingTaskDueDate = Cache::remember(
            "user_{$userId}_next_task_due",
            60,
            function () use ($user) {
                $queriedNextPendingTaskDueDate = $user
                    ->tasks()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()?->due_date;

                return $queriedNextPendingTaskDueDate;
            }
        );

        // Return the view with the data
        return view(
            'wallet.show',
            compact(
                'balance',
                'full_name',
                'nextPendingBillDueDate',
                'nextPendingTaskDueDate',
                'lastTransaction'
            )
        );
    }
}
