<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
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
        'password',
    ];

    public static $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'birthdate' => 'required|date',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
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

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
