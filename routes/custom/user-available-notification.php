<?php

use App\Http\Controllers\UserAvailableNotificationController;

Route::get('/user-available-notifications', [
    UserAvailableNotificationController::class,
    'index',
])
    ->middleware('auth')
    ->name('user-available-notifications.index');

Route::post('/user-available-notifications/save-preferences', [
    UserAvailableNotificationController::class,
    'savePreferences',
])
    ->middleware('auth')
    ->name('user-available-notifications.savePreferences');
