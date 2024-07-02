<?php

use Illuminate\Support\Facades\Route;
use App\Charts\YearlyTransactionsChart;
use App\Http\Controllers\BillChartController;
use App\Http\Controllers\TransactionChartController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $chart = new YearlyTransactionsChart('1');
        $chart->buildChart();

        return view('dashboard', [
            'labels' => $chart->labels,
            'datasetLabel' => $chart->datasetLabel,
            'data' => $chart->data,
        ]);
    })->name('dashboard');

    Route::post('/api/charts/transactions', [
        TransactionChartController::class,
        'getTransactions',
    ]);
    Route::post('/api/charts/bills', [BillChartController::class, 'getBills']);
});
