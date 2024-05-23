<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionCategory extends Model
{
    use HasFactory;

    protected $table = 'transaction_categories';

    protected $fillable = ['name'];

    public static $rules = [
        'name' => 'required|string|max:255',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
