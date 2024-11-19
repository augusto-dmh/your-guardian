<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAvailableNotificationController extends Controller
{
    public function store(Request $request)
    {
        DB::table('user_available_notifications')
            ->updateOrInsert([
                'user_id' => $request->user_id,
                'available_notification_id' => $request->available_notification_id
            ]);

        return redirect()->back()->with('success', __('Notifications preferences updated successfully'));
    }

    public function destroy(Request $request)
    {
        DB::table('user_available_notifications')
            ->where('user_id', $request->user_id)
            ->where('available_notification_id', $request->available_notification_id)
            ->delete();

        return redirect()->back()->with('success', __('Notifications preferences updated successfully'));
    }
}
