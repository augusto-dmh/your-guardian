<?php

namespace App\Models;

use App\Models\Bill;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class User extends Authenticatable implements MustVerifyEmail
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

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getBalanceAttribute()
    {
        $balance = $this->transactions()->sum('amount');
        return number_format($balance, 2);
    }

    public function getHasTransactionsOrPaidBillsAttribute()
    {
        return $this->transactions()->count() > 0 ||
            $this->bills()->where('paid_at', '!=', null)->count() > 0 ||
            $this->tasks()->count() > 0;
    }

    public function getLastTransactionAttribute()
    {
        return $this->transactions()->latest('created_at')->first();
    }

    public function getBillsPercentagePerStatusAttribute()
    {
        $pendingBillsCount = $this->bills()
            ->where('status', 'pending')
            ->count();
        $paidBillsCount = $this->bills()->where('status', 'paid')->count();
        $overdueBillsCount = $this->bills()
            ->where('status', 'overdue')
            ->count();
        $totalCount = $pendingBillsCount + $paidBillsCount + $overdueBillsCount;

        $pendingBillsPercentage = ($pendingBillsCount / $totalCount) * 100;
        $paidBillsPercentage = ($paidBillsCount / $totalCount) * 100;
        $overdueBillsPercentage = ($overdueBillsCount / $totalCount) * 100;

        return "$pendingBillsPercentage x $paidBillsPercentage x $overdueBillsPercentage";
    }

    public function getTransactionCategoryWithMostTransactionsAttribute()
    {
        return $this->transactions()
            ->select('transaction_category_id', DB::raw('count(*) as total'))
            ->whereNotNull('transaction_category_id')
            ->groupBy('transaction_category_id')
            ->orderBy('total', 'desc')
            ->first()->transactionCategory->name;
    }

    public function getTransactionsPercentagePerTypeAttribute()
    {
        $incomeCount = $this->transactions()->where('type', 'income')->count();
        $expenseCount = $this->transactions()
            ->where('type', 'expense')
            ->count();
        $totalCount = $incomeCount + $expenseCount;

        $incomePercentage = ($incomeCount / $totalCount) * 100;
        $expensePercentage = ($expenseCount / $totalCount) * 100;

        return "$incomePercentage x $expensePercentage";
    }

    public function getNextPendingBillDueDateAttribute()
    {
        return $this->bills()
            ->where('due_date', '>=', now())
            ->where('status', '=', 'pending')
            ->orderBy('due_date', 'asc')
            ->first()?->due_date;
    }

    public function getNextPendingTaskDueDateAttribute()
    {
        return $this->tasks()
            ->where('due_date', '>=', now())
            ->where('status', '=', 'pending')
            ->orderBy('due_date', 'asc')
            ->first()?->due_date;
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
}
