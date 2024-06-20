<?php

use Faker\Factory;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;

test('transactions.index screen filters transactions correctly', function () {
    $faker = Factory::create();

    $user = User::factory()->create();
    Auth::login($user);

    $transactionCategory = TransactionCategory::factory()->create();
    $includedTransactions = Transaction::factory(5)->create([
        'user_id' => $user->id,
        'transaction_category_id' => $transactionCategory->id,
        'type' => 'income',
    ]);
    $excludedTransactions = Transaction::factory(5)->create([
        'user_id' => $user->id,
        'transaction_category_id' => $transactionCategory->id,
        'type' => 'expense',
    ]);

    $response = $this->actingAs($user)->get(
        route('transactions.index', [
            'filterByType' => 'income',
        ])
    );

    $response->assertViewHas('transactions', function ($viewTransactions) use (
        $includedTransactions,
        $excludedTransactions
    ) {
        foreach ($includedTransactions as $transaction) {
            if (!$viewTransactions->contains($transaction)) {
                return false;
            }
        }

        foreach ($excludedTransactions as $transaction) {
            if ($viewTransactions->contains($transaction)) {
                return false;
            }
        }

        return true;
    });
});
