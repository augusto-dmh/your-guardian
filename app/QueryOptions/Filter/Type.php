<?php

namespace App\QueryOptions\Filter;

class Type
{
    private $filterByType;

    public function __construct($filterByType)
    {
        $this->filterByType = $filterByType;
    }

    public function handle($query, $next)
    {
        if (!empty($this->filterByType)) {
            $query->whereIn('type', $this->filterByType);
        }

        return $next($query);
    }
}
