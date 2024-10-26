<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Faker\Factory;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('bill successfully stored', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);
    $billData = [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ];

    $response = $this->actingAs($user)->post(
        route('bills.store'),
        $billData
    );

    $response->assertStatus(302);
    // redirected
    $this->assertDatabaseHas('bills', $billData);
});

test('handle bill cache successfully working on storing abill', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);

    $this->actingAs($user)->post(route('bills.store'), [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ]);
    $this->actingAs($user)->post(route('bills.store'), [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(2)->toDateString(),
        'status' => 'pending',
    ]);
    $BillCreatedOnSecondRequest = $user->bills()->latest('id')->first();

    expect(formatDate($BillCreatedOnSecondRequest->due_date))->toEqual(formatDate(Cache::get("user_{$user->id}_next_bill_due")));
});