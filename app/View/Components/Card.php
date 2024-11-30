<?php

namespace App\View\Components;

use Closure;
use App\Models\Bill;
use App\Models\Task;
use App\Models\Transaction;
use InvalidArgumentException;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Card extends Component
{
    public $instance;
    public $modelName;

    public function __construct($instance, $modelName)
    {
        $this->instance = $instance;
        $this->modelName = $modelName;
    }

    public function render(): View|Closure|string
    {
        return view('components.card');
    }
}
