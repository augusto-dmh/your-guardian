<?php

use App\Models\User;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Faker\Factory as Faker;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Faker::create();
});

test('bill successfully updated', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = formatDate(now()->addDays(2));
    $response = $this->actingAs($user)->put(route('bills.update', $bill), [
        'due_date' => $newDueDate,
    ]);
    $bill->refresh();

    expect(formatDate($bill->due_date))->toEqual($newDueDate);
});

test('handle bill cache successfully working on updating abill', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = formatDate(now()->addDays(2));
    $response = $this->actingAs($user)->put(route('bills.update', $bill), [
        'due_date' => $newDueDate,
    ]);
    $bill->refresh();

    expect(formatDate(Cache::get("user_{$bill->user_id}_next_bill_due")) ==
        formatDate($bill->due_date))->toBeTrue();
});