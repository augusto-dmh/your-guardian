<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class dashboardCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $title, public mixed $data)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-card', [
            // i need to explicit this here because a conflict happens if Laravel alone tries to.
            'title' => $this->title, // probably because 'view' has one of its parameters as 'data' and receives 'data' attribute from the class. If i change from
            'data' => $this->data, // 'data' to something else no problem occurs.
        ]);
    }
}
