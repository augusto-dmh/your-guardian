<?php

use App\Models\Bill;
use App\Models\User;
use Faker\Factory as Faker;

use Illuminate\Support\Arr;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertStringContainsString;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Faker::create();
});

test('cant bill be accessed by guest', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = get(route('bills.show', ['bill' => $bill]));

    $response
        ->assertRedirectToRoute('login');
});

test('can bill be accessed by the user who created it', function () {
    $user = User::factory()->create();
    $userBill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->get(route('bills.show', ['bill' => $userBill]));

    $response
        ->assertOk()
        ->assertViewHas('bill', function ($bill) use ($userBill) {
            return $bill->is($userBill);
        });
});

test('cant bill be accessed by a user that has not created it', function () {
    $userWhoCreatedBill = User::factory()->create();
    $userWhoHasntCreatedBill = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $userWhoCreatedBill]);

    $response = actingAs($userWhoHasntCreatedBill)->get(route('bills.show', ['bill' => $bill]));

    $response
        ->assertForbidden();
});

test('are bill required attributes values seen in show view', function () { // required = that should be present in the view
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);
    $requiredAttributes = Arr::except($bill->attributesToArray(), ['user_id', 'updated_at', 'id', 'paid_at']); // paid_at is checked in a separate test for paid bill
    $requiredAttributes['created_at'] = formatDate($requiredAttributes['created_at']);
    $requiredAttributes['due_date'] = formatDate($requiredAttributes['due_date']);

    $response = actingAs($user)->get(route('bills.show', ['bill' => $bill]));

    foreach ($requiredAttributes as $attribute => $value) {
        $response
            ->assertSee($value);
    }
});

test('is paid_at attribute showed for a paid bill in show view', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'paid']);

    $response = actingAs($user)->get(route('bills.show', ['bill' => $bill]));

    $response
        ->assertSee('Paid at')
        ->assertSee(formatDate($bill->paid_at));
});

test('can bill be paid in show view', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $response = actingAs($user)->get(route('bills.show', ['bill' => $bill]));

    $response->assertSeeInOrder([
        '<form',
        'action="' . route('bills.update', ['bill' => $bill]) . '"',

        '<input',
        'type="hidden"',
        'name="_token"',

        '<input',
        'type="hidden"',
        'name="status"',
        'value="paid"',

        '<button',
        'type="submit"',
    ], false);
    assertStringContainsString('method="post"', strtolower($response->getContent())); // perhaps it would be better to determine as pattern in a next commit the methods being always in capslock, instead of relying on this approach
    assertStringContainsString('<input type="hidden" name="_method" value="put"', strtolower($response->getContent()));
});

test('can bill be deleted in show view', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->get(route('bills.show', ['bill' => $bill]));

    $response
        ->assertSeeInOrder([
            '<form',
            'action="' . route('bills.destroy', ['bill' => $bill]) . '"',

            '<input',
            'type="hidden"',
            'name="_token"',

            '<button',
            'type="submit"'
        ], false);
    assertStringContainsString('method="post"', strtolower($response->getContent()));
    assertStringContainsString('<input type="hidden" name="_method" value="delete"', strtolower($response->getContent()));
});

test('can go to edit view in show view', function () {
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);

    $response = actingAs($user)->get(route('bills.show', ['bill' => $bill]));

    $response
        ->assertSeeInOrder([
            '<a',
            'href="' . route('bills.edit', ['bill' => $bill]) . '"',
        ], false);
});
