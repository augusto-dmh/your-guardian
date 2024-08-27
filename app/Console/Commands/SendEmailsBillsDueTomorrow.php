<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\BillDueTomorrowNotification;
use App\Notifications\BillsDueTomorrowNotification;

class SendEmailsBillsDueTomorrow extends Command
{
    protected $signature = 'send-emails:bills-due-tomorrow';

    protected $description = 'Send a notification for bills due tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow();
        $users = User::all();

        foreach ($users as $user) {
            $bills = $user
                ->bills()
                ->where('status', '!=', 'paid')
                ->whereDate('due_date', $tomorrow)
                ->get();

            if ($bills->isEmpty()) {
                return;
            }

            count($bills) > 1
                ? $user->notify(
                    new BillsDueTomorrowNotification(
                        $bills,
                        $user->language_preference
                    )
                )
                : $user->notify(
                    new BillDueTomorrowNotification(
                        $bills->first(),
                        $user->language_preference
                    )
                );
        }
    }
}
