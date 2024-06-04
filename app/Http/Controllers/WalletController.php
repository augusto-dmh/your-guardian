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

        $balance = $user->balance;

        $full_name = $user->full_name;

        $bill = $user->bills()->orderBy('due_date', 'asc')->first();
        $nextBillDueDate = $bill ? $bill->due_date->format('Y-m-d') : 'none';

        $task = $user->tasks()->orderBy('due_date', 'asc')->first();
        $nextTaskDueDate = $task ? $task->due_date->format('Y-m-d') : 'none';

        $lastTransaction = $user->transactions->last();

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
