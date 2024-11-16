<?php

use App\Models\Bill;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\followingRedirects;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can edit view be accessed by bill owner', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->get(route('bills.edit', $bill));

    $response
        ->assertViewIs('bills.edit');
});

test('cant edit view be accessed by a guest', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = get(route('bills.edit', $bill));

    $response
        ->assertRedirectToRoute('login');
});

test('cant edit view be accessed by bill non-owner', function () {
    $owner = User::factory()->create();
    $nonowner = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $owner->id]);

    $response = actingAs($nonowner)->get(route('bills.edit', $bill));

    $response
        ->assertForbidden();
});

test('are bill fillable non-fk attributes accessible in inputs from edit view', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);
    $attributes = (new Bill())->getFillable();
    $nonFkAttributes = array_filter($attributes, function ($value) {
        return substr($value, -3) !== '_id';
    });

    $response = actingAs($user)->get(route('bills.edit', $bill));

    foreach ($nonFkAttributes as $attribute) {
        $response
            ->assertSee($attribute);
    }
});

test('are bill validation errors shown in edit view after failed update', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = followingRedirects()
        ->actingAs($user)
        ->from(route('bills.edit', $bill))
        ->put(route('bills.update', $bill), [
            'amount' => 'invalid amount',
            'title' => str_repeat('a', 256),
            'description' => str_repeat('a', 65536),
            'status' => 'invalid status',
            'due_date' => 'invalid due date',
        ]);

    // there's no "assertSeeTimes" in Laravel http tests: this is a workaround to check if validation error messages appear how much they should
    $response
        ->assertSeeInOrder([
            '<p class="text-red-500">',
            '<p class="text-red-500">',
            '<p class="text-red-500">',
            '<p class="text-red-500">'
        ], false);
    // 4 instead of 5 because no error message is shown on invalid status (since there's no way to get this error but when trying to tamper with html,
    // there's no need to show the cause of the update not being a success
});

test('are input values persisted in edit view after failed update', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = followingRedirects()
        ->actingAs($user)
        ->from(route('bills.edit', $bill))
        ->put(route('bills.update', $bill), [
            'amount' => 'invalid amount',
            'title' => str_repeat('a', 256),
            'description' => str_repeat('a', 65536),
            'status' => 'invalid status',
            'due_date' => 'invalid due date',
        ]);

    $response
        ->assertSee('invalid amount')
        ->assertSee(str_repeat('a', 256))
        ->assertSee(str_repeat('a', 65536))
        ->assertSee('invalid due date');
});
