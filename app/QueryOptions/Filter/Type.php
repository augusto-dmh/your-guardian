<?php

namespace App\QueryOptions\Filter;

class Type
{
    public function handle($query, $next)
    {
        request()->has('filterByType') &&
            $query->whereIn('type', request()->input('filterByType'));

        return $next($query);
    }
}
