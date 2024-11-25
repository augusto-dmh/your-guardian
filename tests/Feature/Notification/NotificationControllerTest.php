<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;
use Illuminate\Support\Facades\Log;
use App\Notifications\BillDueTomorrowNotification;
use Database\Seeders\AvailableNotificationsSeeder;
// 'notification.read'

test('can a notification be read after clicking on it', function () {
    seed(AvailableNotificationsSeeder::class);
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'due_date' => Carbon::tomorrow()]);
    $notification = new BillDueTomorrowNotification($bill, $user->language_preference);
    $user->notify($notification);
    $databaseNotification = $user->notifications->first();

    $response = actingAs($user)->get(route('notification.read', $databaseNotification));
    $databaseNotification->refresh();

    expect($databaseNotification->read_at)
        ->not
        ->toBeNull();
});

test('is user redirected to the url of the notification after it gets read', function () {
    seed(AvailableNotificationsSeeder::class);
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'due_date' => Carbon::tomorrow()]);
    $notification = new BillDueTomorrowNotification($bill, $user->language_preference);
    $user->notify($notification);
    $databaseNotification = $user->notifications->first();

    $response = actingAs($user)->get(route('notification.read', $databaseNotification));

    $response
        ->assertRedirect($notification->toArray($user)['url']);
});
