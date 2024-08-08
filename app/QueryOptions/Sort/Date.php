<?php

namespace App\QueryOptions\Sort;

class Date
{
    public function handle($query, $next)
    {
        if (request()->has('sortByDate')) {
            $sortByDate = request()->input('sortByDate');

            ($sortByDate === 'asc' || $sortByDate === 'desc') &&
                $query->orderBy('created_at', $sortByDate);
        }

        return $next($query);
    }
}
