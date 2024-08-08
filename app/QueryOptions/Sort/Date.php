<?php

namespace App\QueryOptions\Sort;

class Date
{
    private $sortByDate;

    public function __construct($sortByDate)
    {
        $this->sortByDate = $sortByDate;
    }

    public function handle($query, $next)
    {
        if ($this->sortByDate === 'asc' || $this->sortByDate === 'desc') {
            $query->orderBy('created_at', $this->sortByDate);
        }

        return $next($query);
    }
}
