<?php

namespace App\QueryOptions\Sort;

class DueDate
{
    public function handle($query, $next)
    {
        if (request()->has('sortByDueDate')) {
            $sortByDueDate = request()->input('sortByDueDate');

            ($sortByDueDate === 'asc' || $sortByDueDate === 'desc') &&
                $query->orderBy('due_date', $sortByDueDate);
        }

        return $next($query);
    }
}
