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

if (!function_exists('getDateFormatForChartDataQuery')) {
    function getDateFormatForChartDataQuery($format = 'Y-m-d')
    {
        $locale = app()->getLocale();

        if ($format === 'Y-m') {
            return $locale === 'pt_BR' ? '%m-%Y' : '%Y-%m';
        }
        return $locale === 'pt_BR' ? '%d-%m-%Y' : '%Y-%m-%d';
    }
}
