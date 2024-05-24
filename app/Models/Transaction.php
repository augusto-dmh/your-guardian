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
        'bill_id',
        'transaction_category_id',
        'amount',
        'type',
    ];

    public static $rules = [
        'wallet_id' => 'required|exists:wallets,id',
        'bill_id' => 'exists:bills,id',
        'transaction_category_id' => 'exists:transaction_categories,id',
        'amount' => 'required|numeric',
        'type' => 'string|in:income,expense',
    ];

    protected $attributes = [
        'type' => 'expense',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function transactionCategory()
    {
        return $this->belongsTo(TransactionCategory::class);
    }
}
