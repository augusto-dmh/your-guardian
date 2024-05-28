<?php

use App\Http\Controllers\TransactionController;

Route::get('/transactions/create', [TransactionController::class, 'create'])
    ->middleware('auth')
    ->name('transactions.create');

Route::post('/transactions', [TransactionController::class, 'store'])
    ->middleware('auth')
    ->name('transactions.store');

Route::get('/transactions', [TransactionController::class, 'index'])
    ->middleware('auth')
    ->name('transactions.index');

Route::get('transactions/{transaction}/edit', [
    TransactionController::class,
    'edit',
])
    ->middleware('auth')
    ->name('transactions.edit');

Route::get('/transactions/{transaction}', [
    TransactionController::class,
    'show',
])
    ->middleware('auth')
    ->name('transactions.show');

Route::put('/transactions/{transaction}', [
    TransactionController::class,
    'update',
])
    ->middleware('auth')
    ->name('transactions.update');

Route::delete('/transactions/{transaction}', [
    TransactionController::class,
    'destroy',
])
    ->middleware('auth')
    ->name('transactions.destroy');
