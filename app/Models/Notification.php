<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
    ];

    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'message' => 'required|string|max:255',
        'is_read' => 'required|boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
