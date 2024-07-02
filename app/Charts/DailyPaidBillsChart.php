<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Auth;

class DailyPaidBillsChart extends Chart
{
    public $datasetLabel;
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

        $bills = $this->user
            ->bills()
            ->whereBetween('paid_at', [$startDate, now()])
            ->selectRaw('paid_at, COUNT(*) as count_paid')
            ->groupBy('paid_at')
            ->orderBy('paid_at')
            ->get();

        $this->datasetLabel = 'Bills paid';
        $this->labels = $bills->pluck('paid_at');
        $this->data = $bills->pluck('count_paid');
        // $this->labels($bills->pluck('paid_at'));
        // $this->dataset('Daily paid bills', 'line', $bills->pluck('count_paid'));
    }
}
