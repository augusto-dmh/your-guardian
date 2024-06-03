<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\BillDueTomorrow;
use App\Notifications\BillsDueTomorrow;

class SendBillDueTomorrowNotifications extends Command
{
    protected $signature = 'send:bill-due-tomorrow-notifications';

    protected $description = 'Send a notification for bills due tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow();

        $users = User::all();

        foreach ($users as $user) {
            $bills = $user->bills()->whereDate('due_date', $tomorrow)->get();

            if ($bills->isEmpty()) {
                continue;
            }

            count($bills) > 1
                ? $user->notify(new BillsDueTomorrow($bills))
                : $user->notify(new BillDueTomorrow($bills->first()));
        }
    }
}
