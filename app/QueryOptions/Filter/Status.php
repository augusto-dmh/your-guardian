<?php

namespace App\QueryOptions\Filter;

class Status
{
    public function handle($query, $next)
    {
        if (request()->has('filterByStatus')) {
            $filterByStatus = request()->input('filterByStatus');

            if (is_array($filterByStatus)) {
                $query->whereIn('status', $filterByStatus);
            } else {
                $query->where('status', $filterByStatus);
            }
        }

        return $next($query);
    }
}
