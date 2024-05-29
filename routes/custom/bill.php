<?php

use App\Http\Controllers\BillController;

Route::get('/bills/create', [BillController::class, 'create'])
    ->middleware('auth')
    ->name('bills.create');

Route::post('/bills', [BillController::class, 'store'])
    ->middleware('auth')
    ->name('bills.store');

Route::get('/bills', [BillController::class, 'index'])
    ->middleware('auth')
    ->name('bills.index');

Route::get('bills/{transaction}/edit', [
    BillController::class,
    'edit',
])
    ->middleware('auth')
    ->name('bills.edit');

Route::get('/bills/{transaction}', [
    BillController::class,
    'show',
])
    ->middleware('auth')
    ->name('bills.show');

Route::put('/bills/{transaction}', [
    BillController::class,
    'update',
])
    ->middleware('auth')
    ->name('bills.update');

Route::delete('/bills/{transaction}', [
    BillController::class,
    'destroy',
])
    ->middleware('auth')
    ->name('bills.destroy');
