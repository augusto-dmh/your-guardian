<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\seed;
use function Pest\Laravel\artisan;
use App\Models\AvailableNotification;
use Database\Seeders\AvailableNotificationsSeeder;

test('can users with "Bills Due Tomorrow" notification enabled receive it in-app', function () {
    seed(AvailableNotificationsSeeder::class);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(1);
});

test('cant users with "Bills Due Tomorrow" notification disabled receive it in-app', function () {
    seed(AvailableNotificationsSeeder::class);
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});

test('cant users with "Bills Due Tomorrow" notification enabled receive it in-app without any bills whose due date is tomorrow', function () {
    seed(AvailableNotificationsSeeder::class);
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'), 'user_id' => $user->id]);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});
