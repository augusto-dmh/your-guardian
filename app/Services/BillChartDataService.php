<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class BillChartDataService
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function getNumberOfDailyPaidBills($intervalLength = 1)
    {
        $startDate = now()->subYears($intervalLength)->startOfDay();
        $dateFormat = getDateFormatForChartDataQuery();

        $bills = $this->user
            ->bills()
            ->whereBetween('paid_at', [$startDate, now()])
            ->selectRaw(
                "DATE_FORMAT(paid_at, '{$dateFormat}') as date, COUNT(*) as count_paid"
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $bills->pluck('date'),
            'dataset' => [
                'label' => __('Nº of paid bills (daily)'),
                'data' => $bills->pluck('count_paid'),
                'showLine' => false,
            ],
        ];
    }

    public function getNumberOfMonthlyPaidBills($intervalLength = 1)
    {
        $startDate = now()->subYears($intervalLength)->startOfDay();
        $dateFormat = getDateFormatForChartDataQuery('Y-m');

        $bills = $this->user
            ->bills()
            ->whereBetween('paid_at', [$startDate, now()])
            ->selectRaw(
                "DATE_FORMAT(paid_at, '{$dateFormat}') as month, COUNT(*) as count_paid"
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $bills->pluck('month'),
            'dataset' => [
                'label' => __('Nº of paid bills (monthly)'),
                'data' => $bills->pluck('count_paid'),
            ],
        ];
    }

    public function getNumberOfYearlyPaidBills($intervalLength = 1)
    {
        $startDate = now()->subYears($intervalLength)->startOfDay();

        $bills = $this->user
            ->bills()
            ->whereBetween('paid_at', [$startDate, now()])
            ->selectRaw('YEAR(paid_at) as year, COUNT(*) as count_paid')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $bills->pluck('year'),
            'dataset' => [
                'label' => __('Nº of paid bills (yearly)'),
                'data' => $bills->pluck('count_paid'),
            ],
        ];
    }
}
