<?php

namespace App\QueryOptions\Filter;

class Status
{
    public function handle($query, $next)
    {
        request()->has('filterByStatus') &&
            $query->whereIn('status', request()->input('filterByStatus'));

        return $next($query);
    }
}
