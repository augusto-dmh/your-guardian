<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use App\Notifications\BillDueTomorrow;
use Illuminate\Queue\SerializesModels;
use App\Notifications\BillsDueTomorrow;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBillsDueTomorrowNotification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $tomorrow = Carbon::tomorrow();

        $bills = $this->user
            ->bills()
            ->where('status', '!=', 'paid')
            ->whereDate('due_date', $tomorrow)
            ->get();

        if ($bills->isEmpty()) {
            return;
        }

        count($bills) > 1
            ? $this->user->notify(
                new BillsDueTomorrow($bills, $this->user->language_preference)
            )
            : $this->user->notify(
                new BillDueTomorrow(
                    $bills->first(),
                    $this->user->language_preference
                )
            );
    }
}
