<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BillChartDataService;

class BillChartController extends Controller
{
    protected $billChartDataService;

    public function __construct(BillChartDataService $billChartDataService)
    {
        $this->billChartDataService = $billChartDataService;
    }

    public function __invoke(Request $request)
    {
        $type = $request->input('type', 'pending');
        $length = (int) $request->input('length', 7);

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

        return response()->json($chartData);
    }
}
