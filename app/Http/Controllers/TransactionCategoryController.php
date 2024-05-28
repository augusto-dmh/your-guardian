<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;

class TransactionCategoryController extends Controller
{
    public function show($type)
    {
        $categories = TransactionCategory::where(
            'transaction_type',
            $type
        )->get();
        return response()->json($categories);
    }
}
