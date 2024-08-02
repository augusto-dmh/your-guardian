<?php

use Illuminate\Support\Facades\Route;
use App\Charts\YearlyTransactionsChart;
use App\Http\Controllers\BillChartController;
use App\Http\Controllers\TransactionChartController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/api/chart-data/transactions', [
        TransactionChartController::class,
        'fetchChartData',
    ]);
    Route::post('/api/chart-data/bills', [
        BillChartController::class,
        'fetchChartData',
    ]);
});
