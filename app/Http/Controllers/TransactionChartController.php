<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Charts\DailyTransactionsChart;
use App\Charts\YearlyTransactionsChart;
use App\Charts\MonthlyTransactionsChart;
use App\Services\TransactionChartDataService;

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
        $chartData = $this->getTransactionChartData(
            $request->input('type', 'income'),
            (int) $request->input('length', 7)
        );

        return response()->json($chartData);
    }

    protected function getTransactionChartData($type, $length)
    {
        switch ($type) {
            case 'income':
                $chartData = $this->transactionChartDataService->getTotalIncomeOnTransactionsInLastDays(
                    $length
                );
                break;
            case 'expense':
                $chartData = $this->transactionChartDataService->getTotalExpenseOnTransactionsInLastDays(
                    $length
                );
                break;
            default:
                $chartData = $this->transactionChartDataService->getTotalIncomeOnTransactionsInLastDays(
                    $length
                );
                break;
        }

        return $chartData;
    }
}
