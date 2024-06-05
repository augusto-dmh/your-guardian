<?php

namespace App\Models;

use App\Models\Bill;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'bill_id',
        'transaction_category_id',
        'amount',
        'type',
        'description',
    ];

    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'bill_id' => 'exists:bills,id',
        'transaction_category_id' => 'exists:transaction_categories,id',
        'amount' => 'required|numeric',
        'type' => 'string|in:income,expense',
        'description' => 'required|string|max:65535',
    ];

    protected $attributes = [
        'type' => 'expense',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
