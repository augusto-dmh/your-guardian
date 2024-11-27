<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\seed;
use function Pest\Laravel\artisan;
use App\Models\NotificationChannel;
use App\Models\AvailableNotification;
use Database\Seeders\NotificationChannelSeeder;
use Database\Seeders\AvailableNotificationsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('can users with in-app notification channel and "Bills Due Tomorrow" notification enabled receive it in-app', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $inAppNotificationChannelId = NotificationChannel::where('name', 'In-app')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);
    $user->enabledNotificationChannels()->attach($inAppNotificationChannelId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(1);
});

test('cant users with in-app notification channel disabled and "Bills Due Tomorrow" notification enabled receive it in-app', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});

test('cant users with in-app notification channel enabled and "Bills Due Tomorrow" notification disabled receive it in-app', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});

test('cant users with in-app notification channel and "Bills Due Tomorrow" notification enabled receive it in-app without any bills in overdue', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});
