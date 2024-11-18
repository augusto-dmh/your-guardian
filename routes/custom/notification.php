<?php

use Illuminate\Support\Facades\App;
use App\Http\Controllers\NotificationController;

Route::get('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notification.read');
