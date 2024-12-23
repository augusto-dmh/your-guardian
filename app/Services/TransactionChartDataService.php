<?php

namespace App\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TransactionChartDataService
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function getTotalIncomeOnTransactionsInLastDays($length)
    {
        $startDate = now()->subDays($length)->startOfDay();
        $endDate = now()->endOfDay();
        $dateFormat = getDateFormatForChartDataQuery();

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'income')
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date, SUM(amount) as total_amount")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $formattedDate = formatDate($date);
            $labels[] = $formattedDate;
            $data[] = $transactions->get(formatDate($date))->total_amount ?? 0;
        }

        return [
            'labels' => $labels,
            'dataset' => [
                'label' =>
                    __('$ of income on transactions') .
                    ' ' .
                    __('in last :days days', ['days' => $length]),
                'data' => $data,
            ],
        ];
    }

    public function getTotalExpenseOnTransactionsInLastDays($length)
    {
        $startDate = now()->subDays($length)->startOfDay();
        $endDate = now()->endOfDay();
        $dateFormat = getDateFormatForChartDataQuery();

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'expense')
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as date, SUM(amount) as total_amount")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $formattedDate = formatDate($date);
            $labels[] = $formattedDate;
            $data[] = $transactions->get(formatDate($date))->total_amount ?? 0;
        }

        return [
            'labels' => $labels,
            'dataset' => [
                'label' =>
                    __('$ of expense on transactions') .
                    ' ' .
                    __('in last :days days', ['days' => $length]),
                'data' => $data,
            ],
        ];
    }

    public function getTotalAmountOnTransactionsDaily($intervalLength = 1)
    {
        $startDate = now()->subYears($intervalLength)->startOfDay();
        $dateFormat = getDateFormatForChartDataQuery();

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw(
                "DATE_FORMAT(created_at, '{$dateFormat}') as year, SUM(amount) as total_amount_paid"
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $transactions->pluck('year'),
            'dataset' => [
                'label' => __('Total $ on transactions (daily)'),
                'data' => $transactions->pluck('total_amount_paid'),
                'showLine' => false,
            ],
        ];
    }

    public function getTotalAmountOnTransactionsMonthly($intervalLength = 1)
    {
        $startDate = now()->subYears($intervalLength)->startOfDay();
        $dateFormat = getDateFormatForChartDataQuery('Y-m');

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw(
                "DATE_FORMAT(created_at, '{$dateFormat}') as year, SUM(amount) as total_amount_paid"
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $transactions->pluck('year'),
            'dataset' => [
                'label' => __('$ paid on transactions (monthly)'),
                'data' => $transactions->pluck('total_amount_paid'),
            ],
        ];
    }

    public function getTotalAmountOnTransactionsYearly($intervalLength = 1)
    {
        $startDate = now()->subYears($intervalLength)->startOfDay();

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw(
                'YEAR(created_at) as year, SUM(amount) as total_amount_paid'
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'labels' => $transactions->pluck('year'),
            'dataset' => [
                'label' => __('$ paid on transactions (yearly)'),
                'data' => $transactions->pluck('total_amount_paid'),
            ],
        ];
    }
}
