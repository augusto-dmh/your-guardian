<?php

namespace App\View\Components;

use Closure;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class CheckboxFilter extends Component
{
    public $fieldName;
    public $fieldValue;

    public function __construct($fieldName, $fieldValue)
    {
        $this->fieldName = Str::plural($fieldName);
        $this->fieldValue = $fieldValue;
    }

    public function render(): View|Closure|string
    {
        return view('components.checkbox-filter');
    }
}
