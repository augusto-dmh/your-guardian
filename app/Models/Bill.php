<?php

namespace App\Models;

use App\Models\User;
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

    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'description' => 'string|max:65535',
        'amount' => 'numeric',
        'due_date' => 'required|date',
        'status' => 'string|in:unpaid,paid,overdue',
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
