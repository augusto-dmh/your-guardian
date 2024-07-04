<?php

namespace App\Charts;

use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class YearlyPaidBillsChart extends Chart
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
            ->selectRaw('YEAR(paid_at) as year, COUNT(*) as count_paid')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $this->labels($bills->pluck('year'));
        $this->dataset('Bills paid', 'line', $bills->pluck('count_paid'));
        $this->options([
            'backgroundColor' => '#FAC189',
            'borderColor' => '#FAC189',
        ]);
    }
}
