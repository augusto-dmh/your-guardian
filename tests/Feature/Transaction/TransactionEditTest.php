<?php

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;

test('transaction edit view successfully showed', function () {
    $user = User::factory()->create();
    Auth::login($user);
    $transactionCategory = TransactionCategory::factory()->create();
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'transaction_category_id' => $transactionCategory->id,
    ]);

    $response = $this->actingAs($user)->get(
        route('transactions.edit', ['transaction' => $transaction->id])
    );

    $response->assertStatus(200);
    $response->assertViewIs('transactions.edit');
    $response->assertViewHas('transaction', $transaction);
    $response->assertSee('form');
});
