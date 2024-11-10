<?php

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use Faker\Factory as Faker;
use function Pest\Laravel\delete;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseEmpty;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Faker::create();
});

test('can the bill be deleted by the owner', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->delete(route('bills.destroy', $bill));

    assertDatabaseEmpty('bills');
});

test('cant the bill be deleted by a guest', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = delete(route('bills.destroy', $bill));

    $response
        ->assertRedirectToRoute('login');
    assertDatabaseHas('bills', $bill->toArray());
});

test('cant the bill be deleted by non-owner', function () {
    $owner = User::factory()->create();
    $nonOwner = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $owner->id]);

    $response = actingAs($nonOwner)->delete(route('bills.destroy', $bill));

    $response
        ->assertForbidden();
});

test('is the user redirected to index when bill deletion happens from show view', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->from(route('bills.show', $bill))->delete(route('bills.destroy', $bill));

    $response
        ->assertRedirectToRoute('bills.index');
});

test('is the user redirected back when bill gets deleted', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->delete(route('bills.destroy', $bill));

    $response
        ->assertRedirect(url()->previous());
});

test('is the due_date in the future and nearest to the present than others of a pending bill forgot in the caching when the bill gets deleted', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'due_date' => Carbon::now()->addDays(3)->format('Y-m-d')]);

    $response = actingAs($user)->delete(route('bills.destroy', $bill));

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBeNull();
});

test('is the due_date in the future and second-nearest to the present than others of a pending bill cached when the bill whose due_date was cached gets deleted', function () {
    $user = User::factory()->create();
    $pendingBillWithNearestDueDateInFuture = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'due_date' => Carbon::now()->addDays(3)->format('Y-m-d')]);
    $pendingBillWithSecondNearestDueDateInFuture = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'due_date' => Carbon::now()->addDays(3)->format('Y-m-d')]);

    $response = actingAs($user)->delete(route('bills.destroy', $pendingBillWithNearestDueDateInFuture));

    expect(Cache::get("user_{$user->id}_next_bill_due"))
        ->toBe($pendingBillWithSecondNearestDueDateInFuture->due_date->format('Y-m-d'));
});
