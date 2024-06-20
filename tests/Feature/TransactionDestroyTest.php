<?php

use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('Cache handled successfully after deleting a transaction', function () {
    $user = User::factory()->create();
    Auth::login($user);
    $transactionCategory = TransactionCategory::factory()->create();
    $transactionStoreData = Transaction::factory()->make([
        'user_id' => $user->id,
        'transaction_category_id' => $transactionCategory->id,
    ]);

    $response1 = $this->actingAs($user)->post(
        route('transactions.store'),
        $transactionStoreData->toArray()
    );
    $transaction = Transaction::first();
    $response2 = $this->actingAs($user)->delete(
        route('transactions.destroy', $transaction)
    );

    $response1->assertStatus(302);
    $response2->assertStatus(302);
    $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
});
