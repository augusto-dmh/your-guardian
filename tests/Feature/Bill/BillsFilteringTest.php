<?php

use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

test('bills.index screen filters bills correctly', function () {
    $faker = Factory::create();

    $user = User::factory()->create();
    Auth::login($user);

    $includedBills = Bill::factory(5)->create([
        'user_id' => $user->id,
        'status' => $faker->randomElement(['pending', 'paid']),
    ]);

    $excludedBills = Bill::factory(5)->create([
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
