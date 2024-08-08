<?php

namespace App\QueryOptions\Sort;

class Amount
{
    private $sortByAmount;

    public function __construct($sortByAmount)
    {
        $this->sortByAmount = $sortByAmount;
    }

    public function handle($query, $next)
    {
        if ($this->sortByAmount === 'asc' || $this->sortByAmount === 'desc') {
            $query->orderBy('amount', $this->sortByAmount);
        }

        return $next($query);
    }
}
