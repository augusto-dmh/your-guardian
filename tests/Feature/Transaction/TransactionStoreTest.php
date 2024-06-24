<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\Bill;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

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
        $type = $this->faker->randomElement(['income', 'expense']);
        $transactionCategory = TransactionCategory::factory()->create([
            'transaction_type' => $type,
        ]);
        $transactionData = [
            'user_id' => $this->user->id,
            'bill_id' => $bill->id,
            'transaction_category_id' => $transactionCategory->id,
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'description' => $this->faker->paragraph,
            'type' => $type,
        ];

        $response = $this->actingAs($this->user)->post(
            route('transactions.store'),
            $transactionData
        );

        $response->assertStatus(302);
        $this->assertDatabaseHas('transactions', $transactionData);
    }
}
