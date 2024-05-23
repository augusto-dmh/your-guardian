<?php

namespace App\Models;

use App\Models\Wallet;
use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'wallet_id',
        'transaction_category_id',
        'amount',
        'type',
    ];

    public static $rules = [
        'wallet_id' => 'required|exists:wallets,id',
        'transaction_category_id' =>
            'required|exists:transaction_categories,id',
        'amount' => 'required|numeric',
        'type' => 'required|string|in:income,expense',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactionCategory()
    {
        return $this->belongsTo(TransactionCategory::class);
    }
}
