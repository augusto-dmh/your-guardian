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
        $chart = $this->getChartByRange(
            $request->input('type', 'yearly'),
            $request->input('length', '1')
        );

        return response()->json([
            'labels' => $chart->labels,
            'label' => $chart->datasets[0]->name,
            'data' => $chart->datasets[0]->values,
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
