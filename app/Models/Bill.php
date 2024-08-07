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
        'amount',
        'title',
        'description',
        'status',
        'due_date',
        'user_id',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
        'paid_at' => 'date:Y-m-d',
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
