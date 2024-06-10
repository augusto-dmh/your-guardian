<?php

namespace App\Models;

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
            if ($bill->due_date->isFuture() && $bill->status == 'pending') {
                $nextPendingBillDueDate = Cache::get(
                    "user_{$bill->user_id}_next_bill_due"
                );

                if (
                    !$nextPendingBillDueDate ||
                    $bill->due_date->format('Y-m-d') < $nextPendingBillDueDate
                ) {
                    Cache::put(
                        "user_{$bill->user_id}_next_bill_due",
                        $bill->due_date->format('Y-m-d'),
                        60
                    );
                }
            }
        });

        self::updated(function ($bill) {
            if ($bill->due_date->isFuture() && $bill->status == 'pending') {
                $nextPendingBillDueDate =
                    Cache::get("user_{$bill->user_id}_next_bill_due") ??
                    ($bill->user
                        ->bills()
                        ->where('due_date', '>=', now())
                        ->where('status', '=', 'pending')
                        ->orderBy('due_date', 'asc')
                        ->first()
                        ?->due_date->format('Y-m-d') ??
                        'none');

                $bill->due_date->format('Y-m-d') < $nextPendingBillDueDate
                    ? Cache::put(
                        "user_{$bill->user_id}_next_bill_due",
                        $bill->due_date->format('Y-m-d'),
                        60
                    )
                    : Cache::add(
                        "user_{$bill->user_id}_next_bill_due",
                        $nextPendingBillDueDate,
                        60
                    );
            }
        });

        self::deleted(function ($bill) {
            if (
                $bill->due_date->isFuture() &&
                $bill->status == 'pending' &&
                Cache::get("user_{$bill->user_id}_next_bill_due") ==
                    $bill->due_date->format('Y-m-d')
            ) {
                Cache::put(
                    "user_{$bill->user_id}_next_bill_due",
                    $bill->user
                        ->bills()
                        ->where('due_date', '>=', now())
                        ->where('status', '=', 'pending')
                        ->orderBy('due_date', 'asc')
                        ->first()
                        ->due_date?->format('Y-m-d') ?? 'none',
                    60
                );
            }
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
