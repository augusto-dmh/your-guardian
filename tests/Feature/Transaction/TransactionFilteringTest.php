<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Factory;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class TransactionFilteringTest extends TestCase
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

    public function testTransactionsIndexScreenFiltersTransactionsCorrectly()
    {
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
    }
}
