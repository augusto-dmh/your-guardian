<?php

namespace App\QueryOptions\Filter;

class Status
{
    public function handle($query, $next)
    {
        request()->has('filterByStatuses') &&
            $query->whereIn('status', request()->input('filterByStatuses'));

        return $next($query);
    }
}
