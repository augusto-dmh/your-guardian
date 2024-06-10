<?php

namespace App\Models;

use App\CacheHandlers\BillCacheHandler;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'amount',
        'due_date',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function ($bill) {
            BillCacheHandler::handleCreatedBill($bill);
        });

        self::updated(function ($bill) {
            BillCacheHandler::handleUpdatedBill($bill);
        });

        self::deleted(function ($bill) {
            BillCacheHandler::handleDeletedBill($bill);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasOne(Transaction::class);
    }
}
