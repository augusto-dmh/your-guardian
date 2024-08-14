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
        'amount',
        'title',
        'description',
        'type',
        'transaction_category_id',
        'bill_id',
        'user_id',
        'created_at', // it matters in TransactionFactory
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
