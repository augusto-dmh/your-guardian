<?php

use App\Http\Controllers\WalletController;

Route::get('/wallet', [WalletController::class, 'show'])
    ->middleware('auth')
    ->name('wallet.show');
