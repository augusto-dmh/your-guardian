<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Notifications\DatabaseNotification;

class DatabaseNotificationPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DatabaseNotification $notification): bool
    {
        return $notification->notifiable_id === $user->id;
    }
}
