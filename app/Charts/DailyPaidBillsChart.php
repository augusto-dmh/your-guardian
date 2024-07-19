<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Auth;

class DailyPaidBillsChart extends Chart
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

        $bills = $this->user
            ->bills()
            ->whereBetween('paid_at', [$startDate, now()])
            ->selectRaw('paid_at, COUNT(*) as count_paid')
            ->groupBy('paid_at')
            ->orderBy('paid_at')
            ->get();

        $this->labels($bills->pluck('paid_at'));
        $this->dataset(
            __('Nº of paid bills (daily)'),
            'line',
            $bills->pluck('count_paid')
        );
        $this->options([
            'backgroundColor' => '#FAC189',
            'borderColor' => '#FAC189',
        ]);
    }
}
