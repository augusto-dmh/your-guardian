<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchInput extends Component
{
    public $searchTerm;

    public function __construct($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function render(): View|Closure|string
    {
        return view('components.search-input');
    }
}
