<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

class TransactionShowTest extends TestCase
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

    public function testTransactionSuccessfullyShowed()
    {
        $transactionCategory = TransactionCategory::factory()->create();
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'transaction_category_id' => $transactionCategory->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('transactions.show', ['transaction' => $transaction->id])
        );

        $response->assertStatus(200);
        $response->assertViewHas('transaction', $transaction);
    }
}
