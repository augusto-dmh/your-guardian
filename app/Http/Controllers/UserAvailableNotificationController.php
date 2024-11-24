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
        Log::info($availableNotifications);
        Log::info($userEnabledNotifications);

        return view('user-available-notifications.index', compact('availableNotifications', 'userEnabledNotifications'));
    }

    private function bulkStore($notificationsIds, $userId)
    {
        foreach ($notificationsIds as $notificationId) {
            DB::table('available_notification_user')
                ->updateOrInsert([
                    'user_id' => $userId,
                    'available_notification_id' => $notificationId
                ]);
        }
    }

    private function bulkDestroy($notificationsIds, $userId)
    {
        DB::table('available_notification_user')
            ->where('user_id', $userId)
            ->whereIn('available_notification_id', $notificationsIds)
            ->delete();
    }

    public function savePreferences(Request $request)
    {
        $userId = auth()->user()->id;
        $availableNotifications = AvailableNotification::query()->pluck('id')->toArray();
        $notificationsToEnabledIds = $request->input('notifications', []);

        $notificationsToDisable = array_diff($availableNotifications, $notificationsToEnabledIds);

        $notificationsToEnabledIds && $this->bulkStore($notificationsToEnabledIds, $userId);
        $notificationsToDisable && $this->bulkDestroy($notificationsToDisable, $userId);

        return redirect()->back()->with('success', __('Notifications preferences updated successfully'));
    }
}
