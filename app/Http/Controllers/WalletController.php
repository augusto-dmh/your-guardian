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
        $nextBillDueDate = Cache::remember(
            "user_{$userId}_next_bill_due",
            60,
            function () use ($user) {
                $bill = $user->bills()->orderBy('due_date', 'asc')->first();
                return $bill ? $bill->due_date->format('Y-m-d') : 'none';
            }
        );
        $nextTaskDueDate = Cache::remember(
            "user_{$userId}_next_task_due",
            60,
            function () use ($user) {
                $task = $user->tasks()->orderBy('due_date', 'asc')->first();
                return $task ? $task->due_date->format('Y-m-d') : 'none';
            }
        );

        // Return the view with the data
        return view(
            'wallet.show',
            compact(
                'balance',
                'full_name',
                'nextBillDueDate',
                'nextTaskDueDate',
                'lastTransaction'
            )
        );
    }
}
