<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectSort extends Component
{
    public $field;

    public function __construct($field)
    {
        $this->field = $field;
    }

    public function render(): View|Closure|string
    {
        return view('components.select-sort');
    }
}
