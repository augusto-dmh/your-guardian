<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\BillsOverdueNotification;

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
            $bills = $user->bills()->where('status', '=', 'overdue')->get();

            if ($bills->isEmpty()) {
                return;
            }

            $user->notify(
                new BillsOverdueNotification($bills, $user->language_preference)
            );
        }
    }
}
