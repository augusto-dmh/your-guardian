<?php

use App\Models\User;
use Faker\Factory as Faker;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Faker::create();
});

test('bill create view successfully showed', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('bills.create'));

    $response->assertStatus(200);
    $response->assertViewIs('bills.create');
    $response->assertSee('form');
});