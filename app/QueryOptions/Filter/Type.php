<?php

namespace App\QueryOptions\Filter;

class Type
{
    public function handle($query, $next)
    {
        if (request()->has('filterByType')) {
            $filterByType = request()->input('filterByType');

            $query->where('type', $filterByType);
        }

        return $next($query);
    }
}
