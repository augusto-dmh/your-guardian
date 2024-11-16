<?php

use Faker\Factory;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can index view be seen by an authenticated user', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('bills.index'));

    $response
        ->assertStatus(200)
        ->assertViewIs('bills.index');
});

test('cant index view be seen by a guest', function () {
    $response = get(route('bills.index'));

    $response->assertRedirectToRoute('login');
});

test('can bills be filtered by due date in ascending order', function () {
    $user = User::factory()->create();
    $billWithRecentDueDate = Bill::factory()->create(['user_id' => $user->id, 'due_date' => now()->format('Y-m-d')]);
    $billWithOldDueDate = Bill::factory()->create(['user_id' => $user->id, 'due_date' => now()->subDecade()->format('Y-m-d')]);

    $response = actingAs($user)->get(route('bills.index', ['sortByDueDate' => 'asc']));

    $response
        ->assertViewHas('bills', function ($bills) use ($billWithRecentDueDate, $billWithOldDueDate) {
            return ($bills->first()->due_date == $billWithOldDueDate->due_date
                && $bills->last()->due_date == $billWithRecentDueDate->due_date);
        });
});

test('can bills be filtered by due date in descending order', function () {
    $user = User::factory()->create();
    $billWithRecentDueDate = Bill::factory()->create(['user_id' => $user->id, 'due_date' => now()->format('Y-m-d')]);
    $billWithOldDueDate = Bill::factory()->create(['user_id' => $user->id, 'due_date' => now()->subDecade()->format('Y-m-d')]);

    $response = actingAs($user)->get(route('bills.index', ['sortByDueDate' => 'desc']));

    $response
        ->assertViewHas('bills', function ($bills) use ($billWithRecentDueDate, $billWithOldDueDate) {
            return ($bills->first()->due_date == $billWithRecentDueDate->due_date
                && $bills->last()->due_date == $billWithOldDueDate->due_date);
        });
});

test('can bills be filtered by amount in ascending order', function () {
    $user = User::factory()->create();
    $billWithLessAmount = Bill::factory()->create(['user_id' => $user->id, 'amount' => 1]);
    $billWithMoreAmount = Bill::factory()->create(['user_id' => $user->id, 'amount' => 100]);

    $response = actingAs($user)->get(route('bills.index', ['sortByAmount' => 'asc']));

    $response
        ->assertViewHas('bills', function ($bills) use ($billWithLessAmount, $billWithMoreAmount) {
            return ($bills->first()->amount == $billWithLessAmount->amount
                && $bills->last()->amount == $billWithMoreAmount->amount);
        });
});

test('can bills be filtered by amount in descending order', function () {
    $user = User::factory()->create();
    $billWithLessAmount = Bill::factory()->create(['user_id' => $user->id, 'amount' => 1]);
    $billWithMoreAmount = Bill::factory()->create(['user_id' => $user->id, 'amount' => 100]);

    $response = actingAs($user)->get(route('bills.index', ['sortByAmount' => 'desc']));

    $response
        ->assertViewHas('bills', function ($bills) use ($billWithMoreAmount, $billWithLessAmount) {
            return ($bills->first()->amount == $billWithMoreAmount->amount
                && $bills->last()->amount == $billWithLessAmount->amount);
        });
});

test('can bills be filtered by status', function () {
    $user = User::factory()->create();
    $billWithMatchStatus = Bill::factory()->create(['user_id' => $user->id, 'status' => 'paid']);
    $billWithNonMatchStatus = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $response = actingAs($user)->get(route('bills.index', ['filterByStatuses' => ['paid']]));

    $response
        ->assertStatus(200)
        ->assertViewHas('bills', function ($bills) use ($billWithMatchStatus) {
            return $bills->count() === 1 && $bills->first()->is($billWithMatchStatus);
        });
});

test('can bills be filtered by a search term', function () {
    $user = User::factory()->create();
    $billWithMatchTitle = Bill::factory()->create(['user_id' => $user->id, 'title' => 'Some title']);
    $billWithNonMatchTitle = Bill::factory()->create(['user_id' => $user->id, 'title' => 'awdadaswa']);

    $response = actingAs($user)->get(route('bills.index', ['searchTerm' => 'Some']));

    $response
        ->assertViewHas('bills', function ($bills) use ($billWithMatchTitle) {
            return $bills->count() === 1 && $bills->first()->is($billWithMatchTitle);
        });
});

test('is the searching result ranking correct', function () {
    $user = User::factory()->create();
    $billWithBothMatches = Bill::factory()->create(['user_id' => $user->id, 'title' => 'Lorem Some Text', 'description' => 'Some Text Ipsum']);
    $billWithTitleMatch = Bill::factory()->create(['user_id' => $user->id, 'title' => 'Lorem Some Text Ipsum']);
    $billWithDescriptionMatch = Bill::factory()->create(['user_id' => $user->id, 'description' => 'Some Text']);
    $billWithNoMatch = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->get(route('bills.index', ['searchTerm' => 'Some Text']));

    $response
        ->assertViewHas('bills', function ($bills) use ($billWithBothMatches, $billWithTitleMatch, $billWithDescriptionMatch, $billWithNoMatch) {
            Log::info($bills->count());
            return $bills->count() === 3
                && $bills->first()->is($billWithBothMatches)
                && $bills->get(1)->is($billWithTitleMatch)
                && $bills->get(2)->is($billWithDescriptionMatch);
        });
});

test('is pagination implemented', function () {
    $user = User::factory()->create();
    $bills = Bill::factory(11)->create(['user_id' => $user->id]);

    $response = actingAs($user)->get(route('bills.index'));

    $response
        ->assertSee('<nav role="navigation" aria-label="Pagination Navigation"', false);
});
