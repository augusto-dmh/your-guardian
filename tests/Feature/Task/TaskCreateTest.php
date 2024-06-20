<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('Task create view successfully showed', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $response = $this->actingAs($user)->get(route('tasks.create'));

    $response->assertStatus(200);
    $response->assertViewIs('tasks.create');
    $response->assertSee('form');
});
