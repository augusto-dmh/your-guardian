<?php

namespace App\QueryOptions\Filter;

class Status
{
    private $filterByStatus;

    public function __construct($filterByStatus)
    {
        $this->filterByStatus = $filterByStatus;
    }

    public function handle($query, $next)
    {
        if (!empty($this->filterByStatus)) {
            $query->whereIn('status', $this->filterByStatus);
        }

        return $next($query);
    }
}
