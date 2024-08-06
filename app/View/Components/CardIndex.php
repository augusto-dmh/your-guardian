<?php

namespace App\View\Components;

use Closure;
use App\Models\Bill;
use App\Models\Task;
use App\Models\Transaction;
use InvalidArgumentException;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class CardIndex extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $entityName,
        public $entityInstance
    ) {
        if (
            !(
                $entityInstance instanceof Transaction ||
                $entityInstance instanceof Bill ||
                $entityInstance instanceof Task
            )
        ) {
            throw new InvalidArgumentException(
                'Entity instance must be an instance of Transaction, Bill, or Task.'
            );
        }

        $this->entityInstance = $entityInstance;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-index');
    }
}
