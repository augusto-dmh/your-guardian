<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\put;
use function Pest\Laravel\from;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can bill be updated with a valid title', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'title' => 'some title',
        'amount' => $bill->amount, // there are required to hold values on updating
        'due_date' => $bill->due_date,
    ]);

    assertDatabaseHas('bills', ['id' => $bill->id, 'title' => 'some title']);
});

test('cant bill be updated with an empty title', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'title' => '',
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertInvalid(['title']);
});

test('cant bill be updated with a title with more than 255 characters.', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'title' => str_repeat('a', 256),
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertInvalid(['title']);
});

test('can bill be updated with a valid description', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'description' => 'some description',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    assertDatabaseHas('bills', ['id' => $bill->id, 'description' => 'some description']);
});

test('cant bill be updated with a description with more than 65535 characters', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'description' => str_repeat('a', 65536),
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertInvalid(['description']);
});

test('can bill be updated with a valid amount', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'title' => $bill->title,
        'amount' => 500.56,
        'due_date' => $bill->due_date,
    ]);

    assertDatabaseHas('bills', ['id' => $bill->id, 'amount' => 500.56]);
});

test('cant bill be updated with a non-numeric amount', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'title' => $bill->title,
        'amount' => 'some invalid amount',
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertInvalid(['amount']);
});

test('can bill be updated with a valid status', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'status' => 'paid',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    assertDatabaseHas('bills', ['id' => $bill->id, 'status' => 'paid']);
});

test('cant bill be updated with an invalid status', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'status' => 'invalid status',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertInvalid(['status']);
});

test('can bill be updated by an authenticated user', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'title' => 'some field must be updated',
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    assertDatabaseHas('bills', ['id' => $bill->id, 'title' => 'some field must be updated']);
});

test('cant bill be updated by a guest', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = put(route('bills.update', $bill), [
        'title' => 'some field must be updated',
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertRedirectToRoute('login');
});

test('can bill be updated by its owner', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'title' => 'some field must be updated',
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    assertDatabaseHas('bills', ['id' => $bill->id, 'title' => 'some field must be updated']);
});

test('cant bill be updated by not owner', function () {
    $ownerUser = User::factory()->create();
    $notOwnerUser = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $ownerUser->id]);

    $response = actingAs($notOwnerUser)->put(route('bills.update', $bill), [
        'title' => 'some field must be updated',
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertForbidden();
});

test('flashes success message when bill is paid', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending']); // the message is not showed if the update was from status 'paid' to 'paid'

    $response = from(route('bills.show', $bill))->actingAs($user)->put(route('bills.update', $bill), [
        'status' => 'paid',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertRedirectToRoute('bills.show', $bill)
        ->assertSessionHas('success');
});

test('isnt flashed a success message when a bill gets paid in edit view', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $response = from(route('bills.edit', $bill))->actingAs($user)->put(route('bills.update', $bill), [
        'status' => 'paid',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    $response
        ->assertRedirectToRoute('bills.edit', $bill)
        ->assertSessionMissing('success');
});

test("isnt cached the due_date of an updated bill that hasn't got pending", function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'status' => 'overdue',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBeNull();
});

test("isnt cached the due_date of a bill that got pending whose due_date isn't in the future", function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'overdue']);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'status' => 'pending',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => Carbon::now()->subDays(3),
    ]);

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBeNull();
});

test("isnt cached the due_date of a bill that got pending whose due_date is in the future but latest than the current one being cached", function () {
    $user = User::factory()->create();
    $soonerDueDateBill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'due_date' => Carbon::now()->addDays(3)]);
    $latestDueDateBill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'overdue']);

    $response = actingAs($user)->put(route('bills.update', $latestDueDateBill), [
        'status' => 'pending',
        'title' => $latestDueDateBill->title,
        'amount' => $latestDueDateBill->amount,
        'due_date' => Carbon::now()->addDays(4),
    ]);
    $latestDueDateBill->refresh();

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->not
        ->toBe($latestDueDateBill->due_date->format('Y-m-d'));
});

test("is cached the due_date of a bill that got pending whose due_date is in the future and soonest than the current one being cached", function () {
    $user = User::factory()->create();
    $soonerDueDateBill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'overdue']);
    $latestDueDateBill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'due_date' => Carbon::now()->addDays(4)]);

    $response = actingAs($user)->put(route('bills.update', $soonerDueDateBill), [
        'status' => 'pending',
        'title' => $soonerDueDateBill->title,
        'amount' => $soonerDueDateBill->amount,
        'due_date' => Carbon::now()->addDays(3),
    ]);
    $soonerDueDateBill->refresh();

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBe($soonerDueDateBill->due_date->format('Y-m-d'));
});

test('when a bill gets paid its paid_at attribute is updated', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $response = actingAs($user)->put(route('bills.update', $bill), [
        'status' => 'paid',
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date,
    ]);
    $bill->refresh();

    expect($bill->paid_at)
        ->not
        ->toBeNull();
});
