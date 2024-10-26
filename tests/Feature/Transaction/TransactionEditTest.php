<?php

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('transaction edit view successfully showed', function () {
    $transactionCategory = TransactionCategory::factory()->create();
    $transaction = Transaction::factory()->create([
        'user_id' => $this->user->id,
        'transaction_category_id' => $transactionCategory->id,
    ]);

    $response = $this->actingAs($this->user)->get(
        route('transactions.edit', ['transaction' => $transaction->id])
    );

    $response->assertStatus(200);
    $response->assertViewIs('transactions.edit');
    $response->assertViewHas('transaction', $transaction);
    $response->assertSee('form');
});