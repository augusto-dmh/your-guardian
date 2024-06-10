<?php

namespace App\Events;

use App\Models\Bill;
use App\Events\EventInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BillDeleted implements EventInterface
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Bill $bill)
    {
        $this->bill = $bill;
    }

    public function getEntity(): Bill
    {
        return $this->bill;
    }
}
