<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\seed;
use function Pest\Laravel\artisan;
use App\Models\NotificationChannel;
use App\Models\AvailableNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BillsOverdueNotification;
use Database\Seeders\NotificationChannelSeeder;
use Database\Seeders\AvailableNotificationsSeeder;

test('can users with in-app notification channel and "Bills Overdue" notification enabled receive it in-app', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsOverdueNotificationId = AvailableNotification::where('name', 'Bills Overdue')->value('id');
    $inAppNotificationChannelId = NotificationChannel::where('name', 'In-app')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsOverdueNotificationId);
    $user->enabledNotificationChannels()->attach($inAppNotificationChannelId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(1);
});

test('cant users with in-app notification channel disabled and "Bills Overdue" notification enabled receive it in-app', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsOverdueNotificationId = AvailableNotification::where('name', 'Bills Overdue')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsOverdueNotificationId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});

test('cant users with in-app notification channel enabled and "Bills Overdue" notification disabled receive it in-app', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $inAppNotificationChannelId = NotificationChannel::where('name', 'In-app')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);
    $user->enabledNotificationChannels()->attach($inAppNotificationChannelId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});

test('cant users with in-app notification channel and "Bills Overdue" notification enabled receive it in-app without any bills in overdue', function () {
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $inAppNotificationChannelId = NotificationChannel::where('name', 'In-app')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $bill = Bill::factory()->create(['status' => 'pending', 'user_id' => $user->id]);
    $user->enabledNotificationChannels()->attach($inAppNotificationChannelId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    expect($user->notifications()->count())
        ->toBe(0);
});

test('can users with e-mail notification channel and "Bills Overdue" notification enabled receive it in e-mail', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsOverdueNotificationId = AvailableNotification::where('name', 'Bills Overdue')->value('id');
    $emailNotificationChannelId = NotificationChannel::where('name', 'E-mail')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $overdueBill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsOverdueNotificationId);
    $user->enabledNotificationChannels()->attach($emailNotificationChannelId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertSentTo(
        $user,
        BillsOverdueNotification::class,
        function ($notification, array $channels) use ($overdueBill) {
            return in_array('mail', $channels);
        }
    );
});

test('cant users with e-mail notification channel enabled and "Bills Overdue" notification disabled receive it in e-mail', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $emailNotificationChannelId = NotificationChannel::where('name', 'E-mail')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $overdueBill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);
    $user->enabledNotificationChannels()->attach($emailNotificationChannelId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertNotSentTo(
        $user,
        BillsOverdueNotification::class,
        function ($notification, array $channels) {
            return in_array('mail', $channels);
        }
    );
});

test('cant users with e-mail notification channel disabled and "Bills Overdue" notification enabled receive it in e-mail', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsOverdueNotificationId = AvailableNotification::where('name', 'Bills Overdue')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $overdueBill = Bill::factory()->create(['status' => 'overdue', 'user_id' => $user->id]);
    $user->enabledNotifications()->attach($billsOverdueNotificationId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertNotSentTo(
        $user,
        BillsOverdueNotification::class,
        function ($notification, array $channels) {
            return in_array('mail', $channels);
        }
    );
});

test('cant users with e-mail notification channel and "Bills Overdue" notification enabled receive it in e-mail without any bills in overdue', function () {
    Notification::fake();
    seed([AvailableNotificationsSeeder::class, NotificationChannelSeeder::class]);
    $billsOverdueNotificationId = AvailableNotification::where('name', 'Bills Overdue')->value('id');
    $emailNotificationChannelId = NotificationChannel::where('name', 'E-mail')->value('id');
    $user = User::withoutEvents(function () {
        return User::factory()->create();
    });
    $user->enabledNotifications()->attach($billsOverdueNotificationId);
    $user->enabledNotificationChannels()->attach($emailNotificationChannelId);

    artisan('send-notifications:bills-overdue');
    artisan('queue:work', ['--stop-when-empty' => true]);

    Notification::assertNotSentTo(
        $user,
        BillsOverdueNotification::class,
        function ($notification, array $channels) {
            return in_array('mail', $channels);
        }
    );
});
