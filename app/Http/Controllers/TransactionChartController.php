<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\YearlyTransactionsChart;
use App\Charts\MonthlyTransactionsChart;
use App\Charts\DailyTransactionsChart;
use App\Services\TransactionChartDataService;
use Illuminate\Support\Facades\Auth;

class TransactionChartController extends Controller
{
    protected $transactionChartDataService;

    public function __construct(
        TransactionChartDataService $transactionChartDataService
    ) {
        $this->transactionChartDataService = $transactionChartDataService;
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
                $chartData = $this->transactionChartDataService->getTotalAmountOnTransactionsDaily(
                    $length
                );
                break;
            case 'monthly':
                $chartData = $this->transactionChartDataService->getTotalAmountOnTransactionsMonthly(
                    $length
                );
                break;
            case 'yearly':
            default:
                $chartData = $this->transactionChartDataService->getTotalAmountOnTransactionsYearly(
                    $length
                );
                break;
        }

        return $chartData;
    }
}
