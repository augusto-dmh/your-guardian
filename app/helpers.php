<?php

use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

if (!function_exists('isPreviousRoute')) {
    function isPreviousRoute($routeName)
    {
        $previousUrl = URL::previous();
        $previousRequest = app('request')->create($previousUrl);

        try {
            $previousRoute = app('router')->getRoutes()->match($previousRequest);
        } catch (NotFoundHttpException $e) {
            return false; // if the previous route doesn't match any route in the application
        }

        return $previousRoute->getName() === $routeName;
    }
}
