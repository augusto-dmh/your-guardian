<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\seed;
use function Pest\Laravel\artisan;
use App\Models\AvailableNotification;
use Database\Seeders\AvailableNotificationsSeeder;

test('can users with "Bills Overdue" notification enabled receive it in-app', function () {
    seed(AvailableNotificationsSeeder::class);
    $billsOverdueNotificationId = AvailableNotification::where('name', 'Bills Overdue')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsOverdueNotificationId);

    artisan('send-emails:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(1);
});

test('cant users with "Bills Overdue" notification disabled receive it in-app', function () {
    seed(AvailableNotificationsSeeder::class);
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);

    artisan('send-emails:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});

test('cant users with "Bills Overdue" notification enabled receive it in-app without any bills in overdue', function () {
    seed(AvailableNotificationsSeeder::class);
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'user_id' => $user->id]);

    artisan('send-emails:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});
