<?php

use App\Http\Controllers\TransactionCategoryController;

Route::get('/transaction-categories/{type}', [
    TransactionCategoryController::class,
    'show',
])
    ->middleware('auth')
    ->name('transaction-categories.show');
