<?php

namespace App\Observers;

use App\Models\User;
use App\Models\AvailableNotification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $availableNotificationsIds = AvailableNotification::query()
            ->pluck('id')
            ->toArray();

        $user->enabledNotifications()->sync($availableNotificationsIds);
    }
}
