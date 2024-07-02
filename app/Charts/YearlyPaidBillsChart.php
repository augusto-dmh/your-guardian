<?php

namespace App\Charts;

use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class YearlyPaidBillsChart extends Chart
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
            ->selectRaw('YEAR(paid_at) as year, COUNT(*) as count_paid')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $this->datasetLabel = 'Bills paid';
        $this->labels = $bills->pluck('year');
        $this->data = $bills->pluck('count_paid');
        // $this->labels($bills->pluck('year'));
        // $this->dataset(
        //     'Yearly paid bills',
        //     'line',
        //     $bills->pluck('count_paid')
        // );
    }
}
