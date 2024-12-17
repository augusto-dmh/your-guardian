<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardIcon extends Component
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
        return view('components.card-icon');
    }
}
