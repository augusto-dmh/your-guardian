<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditFormSelectField extends Component
{
    public $field;
    public $options;

    public function __construct($field, $options)
    {
        $this->field = $field;
        $this->options = $options;
    }

    public function render(): View|Closure|string
    {
        return view('components.edit-form-select-field');
    }
}
