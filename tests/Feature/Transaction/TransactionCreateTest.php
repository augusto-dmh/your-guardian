<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('transaction create view successfully showed', function () {
    $response = $this->actingAs($this->user)->get(
        route('transactions.create')
    );

    $response->assertStatus(200);
    $response->assertViewIs('transactions.create');
    $response->assertSee('form');
});