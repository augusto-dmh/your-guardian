<?php

use Carbon\Carbon;
use Faker\Factory;
use App\Models\Bill;
use App\Models\User;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can bill be created by an authenticated user', function () {
    $user = User::factory()->create();
    $billData = Bill::factory()->make(['title' => 'some title'])->toArray();

    $response = actingAs($user)->post(route('bills.store'), $billData);


    $response
        ->assertRedirect();
    assertDatabaseCount('bills', 1);
    assertDatabaseHas('bills', [
        'title' => 'some title'
    ]);
});

test('cant bill be created by a guest', function () {
    $billData = Bill::factory()->make()->toArray();

    $response = post(route('bills.store'), $billData);

    $response
        ->assertRedirectToRoute('login');
});

test('cant bill be created without a title', function () {
    $user = User::factory()->create();
    $billData = Bill::factory()->make(['title' => ''])->toArray();

    $response = actingAs($user)->post(route('bills.store'), $billData);

    $response
        ->assertInvalid(['title']);
});

test('cant bill be created with a nonexistent status', function () {
    $user = User::factory()->create();
    $billData = Bill::factory()->make(['status' => 'nonexistent status'])->toArray();

    $response = actingAs($user)->post(route('bills.store'), $billData);

    $response
        ->assertInvalid(['status']);
});

test('is cached the due_date of a created pending bill whose due_date is in the future', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
    ]);

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBe($bill->due_date->format('Y-m-d'));
});

test('is cached the due_date of a created pending bill whose due_date is in the future and soonest than the current one being cached', function () {
    $user = User::factory()->create();
    $latestDueDateBill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
    ]);
    $soonerDueDateBill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
    ]);

    expect(Cache::get("user_{$latestDueDateBill->user_id}_next_bill_due"))
        ->toBe($soonerDueDateBill->due_date->format('Y-m-d'));
});

test('isnt cached the due_date of a created non-pending bill', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'paid',
        'due_date' => Carbon::now()->addDays(3)->format('Y-m-d')
    ]);

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBeNull();
});

test("isnt cached the due_date of a created pending bill whose due_date isn't in the future", function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
    ]);

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBeNull();
});

test('isnt cached the due_date of a created pending bill whose due_date is in the future but latest than the current one being cached', function () {
    $user = User::factory()->create();
    $soonerDueDateBill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
    ]);
    $latestDueDateBill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
    ]);

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBe($soonerDueDateBill->due_date->format('Y-m-d'));
});

test('when a paid bill is created its paid_at attribute comes non-null', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'status' => 'paid'
    ]);

    expect($bill->paid_at)
        ->toBeTruthy();
});
