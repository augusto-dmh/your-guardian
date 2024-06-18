<?php

use Faker\Factory;
use App\Models\User;
use App\Events\BillCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

test('Bill successfully stored', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);

    $response = $this->actingAs($user)->post(route('bills.store'), [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ]);

    $response->assertStatus(302); // redirected
    $this->assertDatabaseHas('bills', [
        'user_id' => $user->id,
    ]);
});

test('HandleBillCache successfully working on storing a bill', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);

    $response1 = $this->actingAs($user)->post(route('bills.store'), [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ]);
    $response2 = $this->actingAs($user)->post(route('bills.store'), [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(2)->toDateString(),
        'status' => 'pending',
    ]);
    $BillCreatedOnSecondRequest = $user->bills()->latest('id')->first();

    expect(
        Cache::get("user_{$user->id}_next_bill_due")->format('Y-m-d')
    )->toEqual($BillCreatedOnSecondRequest->due_date->format('Y-m-d'));
});
