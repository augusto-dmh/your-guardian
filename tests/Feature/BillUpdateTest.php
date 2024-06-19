<?php

use Faker\Factory;
use App\Models\User;
use App\Events\BillUpdated;
use App\Listeners\HandleBillCache;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

test('Bill successfully updated', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = now()->addDays(2)->format('Y-m-d');
    $response = $this->actingAs($user)->put(route('bills.update', $bill), [
        'due_date' => $newDueDate,
    ]);
    $bill->refresh();

    $this->assertEquals($newDueDate, $bill->due_date->format('Y-m-d'));
});

test('HandleBillCache successfully working on updating a bill', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = now()->addDays(2)->format('Y-m-d');
    $response = $this->actingAs($user)->put(route('bills.update', $bill), [
        'due_date' => $newDueDate,
    ]);
    $bill->refresh();

    $this->assertTrue(
        Cache::get("user_{$bill->user_id}_next_bill_due")->format('Y-m-d') ==
            $bill->due_date->format('Y-m-d')
    );
});
