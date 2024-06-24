<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

class TransactionEditTest extends TestCase
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

    public function testTransactionEditViewSuccessfullyShowed()
    {
        $transactionCategory = TransactionCategory::factory()->create();
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'transaction_category_id' => $transactionCategory->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('transactions.edit', ['transaction' => $transaction->id])
        );

        $response->assertStatus(200);
        $response->assertViewIs('transactions.edit');
        $response->assertViewHas('transaction', $transaction);
        $response->assertSee('form');
    }
}
