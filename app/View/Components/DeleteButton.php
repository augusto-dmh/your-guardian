<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteButton extends Component
{
    public $model;
    public $modelName;

    /**
     * Create a new component instance.
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->modelName = strtolower(class_basename($model));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.delete-button');
    }
}
