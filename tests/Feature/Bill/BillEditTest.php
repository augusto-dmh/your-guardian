<?php

use App\Models\Bill;
use App\Models\User;
use Faker\Factory as Faker;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Faker::create();
});

test('bill edit view successfully showed', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = $this->get(route('bills.edit', ['bill' => $bill]));

    $response->assertStatus(200);
    $response->assertViewIs('bills.edit');
    $response->assertViewHas('bill', $bill);
    $response->assertSee('form');
});