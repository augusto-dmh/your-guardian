<?php

use Illuminate\Support\Facades\App;
use App\Http\Controllers\NotificationController;

Route::get('/notifications/read/{notification}', [NotificationController::class, 'read'])->name('notification.read');
