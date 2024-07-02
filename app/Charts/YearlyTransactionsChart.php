<?php

namespace App\Charts;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class YearlyTransactionsChart extends Chart
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

        $transactions = $this->user
            ->transactions()
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw(
                'YEAR(created_at) as year, SUM(amount) as total_amount_paid'
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $this->datasetLabel = 'Total paid on transactions';
        $this->labels = $transactions->pluck('year');
        $this->data = $transactions->pluck('total_amount_paid');
        // $this->labels($transactions->pluck('year'));
        // $this->dataset(
        //     'Yearly transactions',
        //     'line',
        //     $transactions->pluck('total_amount_paid')
        // );
    }
}
