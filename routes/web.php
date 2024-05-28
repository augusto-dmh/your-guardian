<?php

use App\Http\Controllers\ProfileController;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name(
        'profile.edit'
    );
    Route::patch('/profile', [ProfileController::class, 'update'])->name(
        'profile.update'
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
        'profile.destroy'
    );
});

require __DIR__ . '/auth.php';

Route::get('/transactions', [TransactionController::class, 'index'])
    ->middleware('auth')
    ->name('transactions.index');

Route::get('/transactions/create', [TransactionController::class, 'create'])
    ->middleware('auth')
    ->name('transactions.create');

Route::post('/transactions', [TransactionController::class, 'store'])
    ->middleware('auth')
    ->name('transactions.store');

Route::get('/transactions/{transaction}', [
    TransactionController::class,
    'show',
])
    ->middleware('auth')
    ->name('transactions.show');

Route::get('transactions/{transaction}/edit', [
    TransactionController::class,
    'edit',
])
    ->middleware('auth')
    ->name('transactions.edit');

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

Route::get('/transaction-categories/{type}', function ($type) {
    $categories = TransactionCategory::where('transaction_type', $type)->get();
    return response()->json($categories);
});
