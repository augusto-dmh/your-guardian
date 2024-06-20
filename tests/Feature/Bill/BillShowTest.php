<?php

use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('Bill successfully showed', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create([
        'user_id' => $user->id,
    ]);
    Auth::login($user);

    $response = $this->actingAs($user)->get(
        route('bills.show', compact('bill'))
    );

    $response->assertStatus(200);
    $response->assertViewHas('bill', $bill);
});
