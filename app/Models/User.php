<?php

namespace App\Models;

use App\Models\Bill;
use App\Models\Task;
use App\Models\Transaction;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\DB;
use App\Models\AvailableNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class User extends Authenticatable implements MustVerifyEmail
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'email',
        'language_preference',
        'index_view_preference',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->first_name . ' ' . $this->last_name
        );
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->transactions()->sum('amount'), 2)
        );
    }

    protected function hasTransactionsOrPaidBills(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->transactions()->count() > 0 ||
                $this->bills()->whereNotNull('paid_at')->count() > 0 ||
                $this->tasks()->count() > 0
        );
    }

    protected function lastTransaction(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->transactions()->latest('created_at')->first()
        );
    }

    protected function billsPercentagePerStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                $pendingBillsCount = $this->bills()
                    ->where('status', 'pending')
                    ->count();
                $paidBillsCount = $this->bills()
                    ->where('status', 'paid')
                    ->count();
                $overdueBillsCount = $this->bills()
                    ->where('status', 'overdue')
                    ->count();
                $totalCount =
                    $pendingBillsCount + $paidBillsCount + $overdueBillsCount;

                return [
                    'pending' => round(
                        ($pendingBillsCount / $totalCount) * 100
                    ),
                    'paid' => round(($paidBillsCount / $totalCount) * 100),
                    'overdue' => round(
                        ($overdueBillsCount / $totalCount) * 100
                    ),
                ];
            }
        );
    }

    protected function transactionCategoryWithMostTransactions(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->transactions()
                    ->select(
                        'transaction_category_id',
                        DB::raw('count(*) as total')
                    )
                    ->whereNotNull('transaction_category_id')
                    ->groupBy('transaction_category_id')
                    ->orderBy('total', 'desc')
                    ->first()?->transactionCategory->name;
            }
        );
    }

    protected function transactionsPercentagePerType(): Attribute
    {
        return Attribute::make(
            get: function () {
                $incomeCount = $this->transactions()
                    ->where('type', 'income')
                    ->count();
                $expenseCount = $this->transactions()
                    ->where('type', 'expense')
                    ->count();
                $totalCount = $incomeCount + $expenseCount;

                return [
                    'income' => round(($incomeCount / $totalCount) * 100, 2),
                    'expense' => round(($expenseCount / $totalCount) * 100, 2),
                ];
            }
        );
    }

    protected function nextPendingBillDueDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->bills()
                ->where('due_date', '>=', now())
                ->where('status', '=', 'pending')
                ->orderBy('due_date', 'asc')
                ->first()?->due_date
        );
    }

    protected function nextPendingTaskDueDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->tasks()
                ->where('due_date', '>=', now())
                ->where('status', '=', 'pending')
                ->orderBy('due_date', 'asc')
                ->first()?->due_date
        );
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function enabledNotifications()
    {
        return $this->belongsToMany(AvailableNotification::class);
    }
}
