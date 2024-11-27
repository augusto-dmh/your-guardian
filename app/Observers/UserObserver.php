<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationChannel;
use App\Models\AvailableNotification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // all users created start with all notifications enabled to be pushed in-app
        $availableNotificationsIds = AvailableNotification::query()
            ->pluck('id')
            ->toArray();
        $notificationChannelDatabase = NotificationChannel::where('slug', 'database')->first();

        $user->enabledNotifications()->sync($availableNotificationsIds);
        $user->enabledNotificationChannels()->attach($notificationChannelDatabase->id);
    }
}
