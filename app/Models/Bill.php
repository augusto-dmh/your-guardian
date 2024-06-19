<?php

namespace App\Models;

use App\CacheHandlers\BillCacheHandler;
use App\Models\User;
use App\Models\Transaction;
use App\Observers\BillObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ObservedBy(BillObserver::class)]
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasOne(Transaction::class);
    }
}
