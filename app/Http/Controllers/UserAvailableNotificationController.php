<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationChannel;
use Illuminate\Support\Facades\Log;
use App\Models\AvailableNotification;

class UserAvailableNotificationController extends Controller
{
    public function index()
    {
        $availableNotifications = AvailableNotification::all();
        $userEnabledNotifications = auth()->user()->enabledNotifications;
        $notificationChannels = NotificationChannel::all();
        $userEnabledNotificationChannels = auth()->user()->enabledNotificationChannels;

        return view('user-available-notifications.index', compact('availableNotifications', 'userEnabledNotifications', 'notificationChannels', 'userEnabledNotificationChannels'));
    }

    public function savePreferences(Request $request)
    {
        $user = auth()->user();
        $notificationsIds = $request->input('notifications', []);
        $notificationChannels = $request->input('notification_channels', []);

        $user->enabledNotifications()->sync($notificationsIds);
        $user->enabledNotificationChannels()->sync($notificationChannels);

        return redirect()->back()->with('success', __('Notifications preferences updated successfully'));
    }
}
