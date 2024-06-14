<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('Bill create view successfully showed', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $response = $this->actingAs($user)->get(route('bills.create'));

    $response->assertStatus(200);
    $response->assertViewIs('bills.create');
    $response->assertSee('form');
});
