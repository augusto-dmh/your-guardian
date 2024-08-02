<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class TransactionChartDataService
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function getTotalAmountOnTransactionsDaily($intervalLength = 1)
    {
        $startDate = now()->subYears($intervalLength)->startOfDay();

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw(
                'DATE(created_at) as year, SUM(amount) as total_amount_paid'
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

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw(
                'DATE_FORMAT(created_at, "%Y-%m") as year, SUM(amount) as total_amount_paid'
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
