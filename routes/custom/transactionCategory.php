<?php

use App\Models\TransactionCategory;

Route::get('/transaction-categories/{type}', function ($type) {
    $categories = TransactionCategory::where('transaction_type', $type)->get();
    return response()->json($categories);
});
