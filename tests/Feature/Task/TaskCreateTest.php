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

test('task create view successfully showed', function () {
    $response = $this->actingAs($this->user)->get(route('tasks.create'));

    $response->assertStatus(200);
    $response->assertViewIs('tasks.create');
    $response->assertSee('form');
});