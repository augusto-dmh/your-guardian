<?php

use Faker\Factory;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('transactions index screen filters transactions correctly', function () {
    $includedTransactions = Transaction::factory()
        ->count(5)
        ->create([
            'user_id' => $this->user->id,
            'transaction_category_id' => TransactionCategory::factory()->create(
                ['transaction_type' => 'income']
            )->id,
            'type' => 'income',
        ]);
    $excludedTransactions = Transaction::factory()
        ->count(5)
        ->create([
            'user_id' => $this->user->id,
            'transaction_category_id' => TransactionCategory::factory()->create(
                ['transaction_type' => 'expense']
            )->id,
            'type' => 'expense',
        ]);

    $response = $this->actingAs($this->user)->get(
        route('transactions.index', [
            'filterByType' => 'income',
        ])
    );

    $response->assertViewHas('transactions', function (
        $viewTransactions
    ) use ($includedTransactions, $excludedTransactions) {
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