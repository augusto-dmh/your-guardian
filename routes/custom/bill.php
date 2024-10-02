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

Route::get('bills/{bill}/edit', [BillController::class, 'edit'])
    ->middleware(['auth', 'store.previous.url.not.edit'])
    ->name('bills.edit');

Route::get('/bills/{bill}', [BillController::class, 'show'])
    ->middleware('auth')
    ->name('bills.show');

Route::put('/bills/{bill}', [BillController::class, 'update'])
    ->middleware('auth')
    ->name('bills.update');

Route::delete('/bills/{bill}', [BillController::class, 'destroy'])
    ->middleware('auth')
    ->name('bills.destroy');
