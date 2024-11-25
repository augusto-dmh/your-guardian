<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReadNotificationRequest;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function read(DatabaseNotification $notification, ReadNotificationRequest $request)
    {
        $notification->markAsRead();

        return redirect($notification->data['url']);
    }
}
