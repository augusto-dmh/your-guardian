<?php

namespace App\View\Components;

use Closure;
use App\Models\Bill;
use App\Models\Task;
use App\Models\Transaction;
use InvalidArgumentException;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class tableIndexColumns extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $entity)
    {
        if (
            !in_array($entity, [Transaction::class, Bill::class, Task::class])
        ) {
            throw new InvalidArgumentException(
                'Entity must be one of Transaction, Bill, or Task class.'
            );
        }

        $this->entity = $entity;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-index-columns');
    }
}
