<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\YearlyTransactionsChart;
use App\Charts\MonthlyTransactionsChart;
use App\Charts\DailyTransactionsChart;
use Illuminate\Support\Facades\Auth;

class TransactionChartController extends Controller
{
    public function getTransactions(Request $request)
    {
        $chart = $this->getChartByRange($request['type'], $request['length']);

        return response()->json([
            'labels' => $chart->labels,
            'datasetLabel' => $chart->datasetLabel,
            'data' => $chart->data,
        ]);
    }

    protected function getChartByRange($type, $length)
    {
        switch ($type) {
            case 'month':
                $chart = new MonthlyTransactionsChart($length);
                break;
            case 'day':
                $chart = new DailyTransactionsChart($length);
                break;
            case 'year':
            default:
                $chart = new YearlyTransactionsChart($length);
                break;
        }

        $chart->buildChart();
        return $chart;
    }
}
