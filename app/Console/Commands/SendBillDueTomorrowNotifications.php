<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Bill;
use Illuminate\Console\Command;
use App\Notifications\BillDueTomorrow;

class SendBillDueTomorrowNotifications extends Command
{
    protected $signature = 'send:bill-due-tomorrow-notifications';

    protected $description = 'Send a notification for bills due tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow();

        $bills = Bill::whereDate('due_date', $tomorrow)->get();

        foreach ($bills as $bill) {
            $bill->user->notify(new BillDueTomorrow($bill));
        }
    }
}
