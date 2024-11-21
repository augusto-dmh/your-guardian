<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvailableNotification extends Model
{
    use HasFactory;

    protected $table = 'available_notifications';

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_available_notifications', 'available_notification_id', 'user_id');
    }
}
