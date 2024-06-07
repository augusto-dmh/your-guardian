<?php

namespace App\QueryOptions\Sort;

class Amount
{
    public function handle($query, $next)
    {
        if (request()->has('sortByAmount')) {
            $sortByAmount = request()->input('sortByAmount');

            ($sortByAmount === 'asc' || $sortByAmount === 'desc') &&
                $query->orderBy('amount', $sortByAmount);
        }

        return $next($query);
    }
}
