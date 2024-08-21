<?php

namespace App\Console\Commands;

use App\Jobs\SendBillsOverdueNotification;
use App\Models\User;
use Illuminate\Console\Command;

class SendEmailsBillsOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-emails:bills-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a notification for bills overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            SendBillsOverdueNotification::dispatch($user);
        }
    }
}
