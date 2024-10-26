<?php

use App\Models\Bill;
use App\Models\User;
use Faker\Factory as Faker;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Faker::create();
});

test('bill successfully showed', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(
        route('bills.show', ['bill' => $bill->id])
    );

    $response->assertStatus(200);
    $response->assertViewHas('bill', $bill);
});