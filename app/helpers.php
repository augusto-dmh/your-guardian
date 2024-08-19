<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        if (!$date) {
            return __('N/A');
        }

        $locale = app()->getLocale();
        $format = $locale === 'pt_BR' ? 'd-m-Y' : 'Y-m-d';

        return Carbon::parse($date)->format($format);
    }
}
