<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\seed;
use function Pest\Laravel\artisan;
use App\Models\NotificationChannel;
use App\Models\AvailableNotification;
use App\Notifications\BillsOverdueNotification;
use Database\Seeders\NotificationChannelSeeder;
use Database\Seeders\AvailableNotificationsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\BillsDueTomorrowNotification;

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

test('can users with e-mail notification channel and "Bills Due Tomorrow" notification enabled receive it in e-mail', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $emailNotificationChannelId = NotificationChannel::where('name', 'E-mail')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $dueTomorrowBill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);
    $user->enabledNotificationChannels()->attach($emailNotificationChannelId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertSentTo(
        $user,
        BillsDueTomorrowNotification::class,
        function ($notification, array $channels) {
            return in_array('mail', $channels);
        }
    );
});

test('cant users with e-mail notification channel enabled and "Bills Due Tomorrow" notification disabled receive it in e-mail', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $emailNotificationChannelId = NotificationChannel::where('name', 'E-mail')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $dueTomorrowBill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotificationChannels()->attach($emailNotificationChannelId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertNotSentTo(
        $user,
        BillsDueTomorrowNotification::class,
        function ($notification, array $channels) {
            return in_array('mail', $channels);
        }
    );
});

test('cant users with e-mail notification channel disabled and "Bills Due Tomorrow" notification enabled receive it in e-mail', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $dueTomorrowBill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertNotSentTo(
        $user,
        BillsDueTomorrowNotification::class,
        function ($notification, array $channels) {
            return in_array('mail', $channels);
        }
    );
});

test('cant users with e-mail notification channel and "Bills Due Tomorrow" notification enabled receive it in e-mail without any bills due tomorrow', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $emailNotificationChannelId = NotificationChannel::where('name', 'E-mail')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);
    $user->enabledNotificationChannels()->attach($emailNotificationChannelId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertNotSentTo(
        $user,
        BillsDueTomorrowNotification::class,
        function ($notification, array $channels) {
            return in_array('mail', $channels);
        }
    );
});

test('has the "Bills Due Tomorrow" notification sent the bills and the user locale', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsDueTomorrowNotificationId = AvailableNotification::where('name', 'Bills Due Tomorrow')->value('id');
    $emailNotificationChannelId = NotificationChannel::where('name', 'E-mail')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $dueTomorrowBill = Bill::factory()->create(['status' => 'pending', 'due_date' => Carbon::tomorrow()->format('Y-m-d'), 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsDueTomorrowNotificationId);
    $user->enabledNotificationChannels()->attach($emailNotificationChannelId);

    artisan('send-notifications:bills-due-tomorrow');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertSentTo(
        $user,
        BillsDueTomorrowNotification::class,
        function ($notification, array $channels) use ($dueTomorrowBill, $user) {
            return $notification->bills->contains(function ($b) use ($dueTomorrowBill) {
                return $b->id === $dueTomorrowBill->id;
            }) && $notification->locale === $user->language_preference && in_array('mail', $channels);
        }
    );
});
