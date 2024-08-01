<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\DailyPaidBillsChart;
use App\Charts\YearlyPaidBillsChart;
use Illuminate\Support\Facades\Auth;
use App\Charts\MonthlyPaidBillsChart;
use App\Services\BillChartDataService;

class BillChartController extends Controller
{
    protected $billChartDataService;

    public function __construct(BillChartDataService $billChartDataService)
    {
        $this->billChartDataService = $billChartDataService;
    }

    public function fetchChartData(Request $request)
    {
        $chartData = $this->getChartDataByInterval(
            $request->input('type', 'yearly'),
            $request->input('length', '1')
        );

        return response()->json($chartData);
    }

    protected function getChartDataByInterval($type, $length)
    {
        switch ($type) {
            case 'daily':
                $chartData = $this->billChartDataService->getNumberOfDailyPaidBills(
                    $length
                );
                break;
            case 'monthly':
                $chartData = $this->billChartDataService->getNumberOfMonthlyPaidBills(
                    $length
                );
                break;
            case 'yearly':
            default:
                $chartData = $this->billChartDataService->getNumberOfYearlyPaidBills(
                    $length
                );
                break;
        }

        return $chartData;
    }
}
