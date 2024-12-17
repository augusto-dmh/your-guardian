<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Cards extends Component
{
    public $instances;
    public $modelName;

    public function __construct($instances)
    {
        $this->instances = $instances;
        $this->modelName = strtolower(class_basename($instances->first()));
    }

    public function render(): View|Closure|string
    {
        return view('components.cards');
    }
}
