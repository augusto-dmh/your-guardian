<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AvailableNotification;

class UserAvailableNotificationController extends Controller
{
    public function index()
    {
        $availableNotifications = AvailableNotification::all();
        $userEnabledNotifications = auth()->user()->enabledNotifications;

        return view('user-available-notifications.index', compact('availableNotifications', 'userEnabledNotifications'));
    }

    public function savePreferences(Request $request)
    {
        $user = auth()->user();
        $notificationsToBeEnabled = $request->input('notifications', []);

        $user->enabledNotifications()->sync($notificationsToBeEnabled);

        return redirect()->back()->with('success', __('Notifications preferences updated successfully'));
    }
}
