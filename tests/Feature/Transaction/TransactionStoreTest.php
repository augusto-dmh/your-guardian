<?php

use Faker\Factory;
use App\Models\Bill;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('transaction successfully stored', function () {
    $bill = Bill::factory()->create(['user_id' => $this->user->id]);
    $type = $this->faker->randomElement(['income', 'expense']);
    $transactionCategory = TransactionCategory::factory()->create([
        'transaction_type' => $type,
    ]);
    $transactionData = [
        'user_id' => $this->user->id,
        'bill_id' => $bill->id,
        'transaction_category_id' => $transactionCategory->id,
        'amount' => $this->faker->randomFloat(2, 0, 1000),
        'description' => $this->faker->paragraph,
        'type' => $type,
    ];
    $expectedData = Arr::except($transactionData, ['amount']);
    $expectedData['amount'] =
        $transactionData['type'] === 'income'
            ? abs($transactionData['amount'])
            : -1 * abs($transactionData['amount']);

    $response = $this->actingAs($this->user)->post(
        route('transactions.store'),
        $transactionData
    );

    $response->assertStatus(302);
    $this->assertDatabaseHas('transactions', $expectedData);
});