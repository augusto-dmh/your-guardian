<?php

use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('Bill edit view successfully showed', function () {
    $user = User::factory()->create();
    Auth::login($user);
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(
        route('bills.edit', compact('bill'))
    );

    $response->assertStatus(200);
    $response->assertViewIs('bills.edit');
    $response->assertViewHas('bill', $bill);
    $response->assertSee('form');
});
