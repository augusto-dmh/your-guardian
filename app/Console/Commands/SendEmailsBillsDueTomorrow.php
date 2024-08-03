<?php

namespace App\Console\Commands;

use App\Jobs\SendBillsDueTomorrowNotification;
use App\Models\User;
use Illuminate\Console\Command;

class SendEmailsBillsDueTomorrow extends Command
{
    protected $signature = 'send-emails:bills-due-tomorrow';

    protected $description = 'Send a notification for bills due tomorrow';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            SendBillsDueTomorrowNotification::dispatch($user);
        }
    }
}
