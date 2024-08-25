<?php

namespace App\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class BillChartDataService
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function getBillsTotalAmountToBePaidInNextDays($length)
    {
        $startDate = now()->startOfDay();
        $endDate = now()->addDays($length)->endOfDay();

        $bills = $this->user
            ->bills()
            ->whereBetween('due_date', [$startDate, $endDate])
            ->where('status', 'pending')
            ->selectRaw('DATE(due_date) as date, SUM(amount) as total_amount')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $formattedDate;
            $data[] = $bills->get($formattedDate)->total_amount ?? 0;
        }

        return [
            'labels' => $labels,
            'dataset' => [
                'label' =>
                    __('$ to pay in bills') .
                    ' ' .
                    __('in next :days days', ['days' => $length]),
                'data' => $data,
            ],
        ];
    }

    public function getBillsTotalAmountPaidInLastDays($length)
    {
        $startDate = now()->subDays($length)->startOfDay();
        $endDate = now()->endOfDay();

        $bills = $this->user
            ->bills()
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->where('status', 'paid')
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total_amount')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $formattedDate;
            $data[] = $bills->get($formattedDate)->total_amount ?? 0;
        }

        return [
            'labels' => $labels,
            'dataset' => [
                'label' =>
                    __('$ paid in bills') .
                    ' ' .
                    __('in last :days days', ['days' => $length]),
                'data' => $data,
            ],
        ];
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
