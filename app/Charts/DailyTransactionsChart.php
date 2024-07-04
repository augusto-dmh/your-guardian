<?php

namespace App\Charts;

use Illuminate\Support\Facades\Auth;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class DailyTransactionsChart extends Chart
{
    public $label;
    public $labels;
    public $data;
    public $user;
    public $intervalLength;

    public function __construct($intervalLength)
    {
        parent::__construct();
        $this->user = Auth::user();
        $this->intervalLength = $intervalLength;
    }

    public function buildChart()
    {
        $startDate = now()
            ->subYears($this->intervalLength)
            ->startOfDay();

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw(
                'DATE(created_at) as year, SUM(amount) as total_amount_paid'
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $this->labels($transactions->pluck('year'));
        $this->dataset(
            'Daily paid bills',
            'line',
            $transactions->pluck('total_amount_paid')
        );
        $this->options([
            'backgroundColor' => '#FAC189',
            'borderColor' => '#FAC189',
        ]);
    }
}
