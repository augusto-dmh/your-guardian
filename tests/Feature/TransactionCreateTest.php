<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('Transaction create view successfully showed', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $response = $this->actingAs($user)->get(route('transactions.create'));

    $response->assertStatus(200);
    $response->assertViewIs('transactions.create');
    $response->assertSee('form');
});
