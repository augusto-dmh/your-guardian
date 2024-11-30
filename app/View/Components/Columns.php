<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Columns extends Component
{
    public $modelName;

    public function __construct($modelName)
    {
        $this->modelName = $modelName;
    }

    public function render(): View|Closure|string
    {
        return view('components.columns');
    }
}
