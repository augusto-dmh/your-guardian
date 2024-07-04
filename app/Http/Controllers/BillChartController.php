<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\DailyPaidBillsChart;
use App\Charts\YearlyPaidBillsChart;
use Illuminate\Support\Facades\Auth;
use App\Charts\MonthlyPaidBillsChart;

class BillChartController extends Controller
{
    public function getBills(Request $request)
    {
        $chart = $this->getChartByRange($request['type'], $request['length']);

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
                $chart = new MonthlyPaidBillsChart($length);
                break;
            case 'day':
                $chart = new DailyPaidBillsChart($length);
                break;
            case 'year':
            default:
                $chart = new YearlyPaidBillsChart($length);
                break;
        }

        $chart->buildChart();
        return $chart;
    }
}
