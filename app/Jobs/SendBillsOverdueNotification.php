<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Notifications\BillsOverdue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBillsOverdueNotification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $bills = $this->user->bills()->where('status', '=', 'overdue')->get();

        if ($bills->isEmpty()) {
            return;
        }

        $this->user->notify(
            new BillsOverdue($bills, $this->user->language_preference)
        );
    }
}
