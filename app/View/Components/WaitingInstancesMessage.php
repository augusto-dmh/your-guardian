<?php

namespace App\View\Components;

use Closure;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class WaitingInstancesMessage extends Component
{
    public $modelInstancesName;

    public function __construct($modelInstances)
    {
        $this->modelInstancesName = Str::plural(class_basename($modelInstances->first()));
    }

    public function render(): View|Closure|string
    {
        return view('components.waiting-instances-message');
    }
}
