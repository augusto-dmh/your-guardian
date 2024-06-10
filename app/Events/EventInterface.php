<?php

namespace App\Events;

use App\Models\Bill;
use App\Models\Task;

interface EventInterface
{
    public function getEntity(): Bill|Task;
}
