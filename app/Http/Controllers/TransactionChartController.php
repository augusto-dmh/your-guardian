<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionChartDataService;

class TransactionChartController extends Controller
{
    protected $transactionChartDataService;

    public function __construct(TransactionChartDataService $transactionChartDataService)
    {
        $this->transactionChartDataService = $transactionChartDataService;
    }

    public function __invoke(Request $request)
    {
        $type = $request->input('type', 'pending');
        $length = (int) $request->input('length', 7);

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

        return response()->json($chartData);
    }
}
