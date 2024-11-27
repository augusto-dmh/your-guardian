<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationChannel extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_channel_user', 'notification_channel_id', 'user_id');
    }
}
