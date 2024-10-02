<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Str;

class EditForm extends Component
{
    public $formAction;
    public $model;
    public $modelName;

    public function __construct($formAction, $model)
    {
        $this->formAction = $formAction;
        $this->model = $model;

        $this->modelName = strtolower(class_basename($model));
    }

    public function render()
    {
        return view('components.edit-form');
    }
}
