<?php

use Faker\Factory;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
});
test('bills index screen filters bills correctly', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $includedBills = Bill::factory()
        ->count(5)
        ->create([
            'user_id' => $user->id,
            'status' => $this->faker->randomElement(['pending', 'paid']),
        ]);

    $excludedBills = Bill::factory()
        ->count(5)
        ->create([
            'user_id' => $user->id,
            'status' => 'overdue',
        ]);

    $response = $this->actingAs($user)->get(
        route('bills.index', [
            'filterByStatus' => ['pending', 'paid'],
        ])
    );

    $response->assertViewHas('bills', function ($viewBills) use (
        $includedBills,
        $excludedBills
    ) {
        foreach ($includedBills as $bill) {
            if (!$viewBills->contains($bill)) {
                return false;
            }
        }

        foreach ($excludedBills as $bill) {
            if ($viewBills->contains($bill)) {
                return false;
            }
        }

        return true;
    });
});
