<?php

namespace App\QueryOptions\Filter;

class Type
{
    public function handle($query, $next)
    {
        request()->has('filterByTypes') &&
            $query->whereIn('type', request()->input('filterByTypes'));

        return $next($query);
    }
}
