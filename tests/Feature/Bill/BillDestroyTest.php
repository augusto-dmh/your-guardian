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

test('bill successfully destroyed', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDay()->toDateString(),
    ]);
    $billData = $bill->toArray();
    $response = $this->actingAs($user)->delete(
        route('bills.destroy', $bill)
    );

    $response->assertStatus(302);
    $this->assertDatabaseMissing('bills', $billData);
});

test('handle bill cache successfully working on updating abill', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $billData = [
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'amount' => $this->faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays()->toDateString(),
        'status' => 'pending',
    ];
    $this->actingAs($user)->post(route('bills.store'), $billData);
    $bill = Bill::latest()->first();
    $this->actingAs($user)->delete(route('bills.destroy', $bill));

    expect(Cache::get("user_{$bill->user_id}_next_bill_due"))->toBeNull();
});