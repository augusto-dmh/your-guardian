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
        $chartData = $this->getBillChartData(
            $request->input('type', 'pending'),
            (int) $request->input('length', 7)
        );

        return response()->json($chartData);
    }

    protected function getBillChartData($type, $length)
    {
        switch ($type) {
            case 'pending':
                $chartData = $this->billChartDataService->getBillsTotalAmountToBePaidInNextDays(
                    $length
                );
                break;
            case 'paid':
                $chartData = $this->billChartDataService->getBillsTotalAmountPaidInLastDays(
                    $length
                );
                break;
            default:
                $chartData = $this->billChartDataService->getBillsTotalAmountToBePaidInNextDays(
                    $length
                );
                break;
        }

        return $chartData;
    }
}
