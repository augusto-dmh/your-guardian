<?php

use App\Models\Bill;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertStringContainsString;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can create view be showed to an authenticated user', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('bills.create'));

    $response
        ->assertStatus(200)
        ->assertViewIs('bills.create');
});


test('cant create view be showed to a guest', function () {
    $response = get(route('bills.create'));

    $response
        ->assertRedirectToRoute('login');
});

test('are fillable not-fk fields displayed in create view', function () {
    $user = User::factory()->create();
    $fillableFields = (new Bill)->getFillable();
    $nonForeignKeyFields = array_filter($fillableFields, function ($field) {
        return !str_ends_with($field, '_id');
    });

    $response = actingAs($user)->get(route('bills.create'));

    $response
        ->assertStatus(200);
    foreach ($nonForeignKeyFields as $field) {
        $labelText = ucwords(str_replace('_', ' ', $field));
        $response
            ->assertSeeText($labelText);
    }
});

test('does the test has a csrf token', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('bills.create'));

    $response
        ->assertSee('<input type="hidden" name="_token"', false);
});

test('does the form action is correct', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('bills.create'));

    $response
        ->assertSee('<form action="' . route('bills.store') . '"', false);
});

test('does the form method is correct', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('bills.create'));

    $responseContent = strtolower($response->getContent());
    assertStringContainsString('<form action="' . route('bills.store') . '" method="post"', $responseContent);
});
