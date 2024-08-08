<?php

namespace App\QueryOptions\Sort;

class DueDate
{
    private $sortByDueDate;

    public function __construct($sortByDueDate)
    {
        $this->sortByDueDate = $sortByDueDate;
    }

    public function handle($query, $next)
    {
        if ($this->sortByDueDate === 'asc' || $this->sortByDueDate === 'desc') {
            $query->orderBy('due_date', $this->sortByDueDate);
        }

        return $next($query);
    }
}
