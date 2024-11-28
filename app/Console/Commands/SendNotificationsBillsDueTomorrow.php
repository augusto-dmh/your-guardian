<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\AvailableNotification;
use App\Notifications\BillsDueTomorrowNotification;

class SendNotificationsBillsDueTomorrow extends Command
{
    protected $signature = 'send-notifications:bills-due-tomorrow';

    protected $description = 'Send a notification for bills due tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow();
        $users = User::whereHas('enabledNotifications', function ($q) {
            $q->where('name', 'Bills Due Tomorrow');
        })->get();

        foreach ($users as $user) {
            $bills = $user
                ->bills()
                ->where('status', '!=', 'paid')
                ->whereDate('due_date', $tomorrow)
                ->get();

            if ($bills->isEmpty()) {
                return;
            }

            $user->notify(
                new BillsDueTomorrowNotification(
                    $bills,
                    $user->language_preference
                )
            );
        }
    }
}
