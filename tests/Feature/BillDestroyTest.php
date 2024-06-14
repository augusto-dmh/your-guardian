<?php

use Faker\Factory;
use App\Models\User;
use App\Events\BillDeleted;
use App\Listeners\HandleBillCache;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

test('Bill successfully destroyed', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDay()->toDateString(),
    ]);
    $response = $this->actingAs($user)->delete(route('bills.destroy', $bill));

    $response->assertStatus(302);
    $this->assertDatabaseMissing('bills', ['id' => $bill->id]);
});

test('HandleBillCache successfully working on updating a bill', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);

    $this->actingAs($user)->post(route('bills.store'), [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays()->toDateString(),
        'status' => 'pending',
    ]);
    $bill = Bill::latest()->first();
    $this->actingAs($user)->delete(route('bills.destroy', $bill));

    $this->assertNull(Cache::get("user_{$bill->user_id}_next_bill_due"));
});

test('BillDeleted event dispatched when bill destroyed', function () {
    Event::fake();
    $user = User::factory()->create();
    Auth::login($user);

    $bill = Bill::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user)->delete(route('bills.destroy', $bill));

    Event::assertDispatched(BillDeleted::class);
});
