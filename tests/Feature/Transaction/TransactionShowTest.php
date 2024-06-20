<?php

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;

test('transaction successfully showed', function () {
    $user = User::factory()->create();
    $transactionCategory = TransactionCategory::factory()->create();
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'transaction_category_id' => $transactionCategory,
    ]);
    Auth::login($user);

    $response = $this->actingAs($user)->get(
        route('transactions.show', ['transaction' => $transaction->id])
    );

    $response->assertStatus(200);
    $response->assertViewHas('transaction', $transaction);
});
