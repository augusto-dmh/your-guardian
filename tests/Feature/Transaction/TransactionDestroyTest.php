<?php

use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('cache handled successfully after deleting atransaction', function () {
    $transactionCategory = TransactionCategory::factory()->create();
    $transaction = Transaction::factory()->create([
        'user_id' => $this->user->id,
        'transaction_category_id' => $transactionCategory->id,
    ]);
    $transactionData = $transaction->toArray();

    $response1 = $this->actingAs($this->user)->post(
        route('transactions.store'),
        $transactionData
    );
    $response2 = $this->actingAs($this->user)->delete(
        route('transactions.destroy', $transaction)
    );

    $response1->assertStatus(302);
    $this->assertDatabaseMissing('transactions', $transactionData);
});