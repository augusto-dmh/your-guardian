<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Factory;
use App\Models\User;
use App\Models\Bill;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class TransactionStoreTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    public function testTransactionSuccessfullyStored()
    {
        $bill = Bill::factory()->create(['user_id' => $this->user->id]);
        $transactionCategory = TransactionCategory::factory()->create();

        $response = $this->actingAs($this->user)->post(
            route('transactions.store'),
            [
                'user_id' => $this->user->id,
                'bill_id' => $bill->id,
                'transaction_category_id' => $transactionCategory->id,
                'amount' => $this->faker->randomFloat(2, 0, 1000),
                'description' => $this->faker->paragraph,
                'type' => 'expense',
            ]
        );

        $transaction = Transaction::latest()->first();

        $response->assertStatus(302);
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
