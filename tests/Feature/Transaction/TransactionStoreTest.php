<?php

use Faker\Factory;
use App\Models\User;
use App\Models\Bill;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;

test('Transaction successfully stored', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    $bill = Bill::factory()->create(['user_id' => $user->id]);
    $transactionCategory = TransactionCategory::factory()->create();
    Auth::login($user);

    $response = $this->actingAs($user)->post(route('transactions.store'), [
        'user_id' => $user->id,
        'bill_id' => $bill->id,
        'transaction_category_id' => $transactionCategory->id,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'description' => $faker->paragraph,
        'type' => 'expense',
    ]);
    $transaction = Transaction::latest()->first();

    $response->assertStatus(302);
    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
    ]);
});
