<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

class TransactionDestroyTest extends TestCase
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

    public function testCacheHandledSuccessfullyAfterDeletingATransaction()
    {
        $transactionCategory = TransactionCategory::factory()->create();
        $transactionStoreData = Transaction::factory()
            ->make([
                'user_id' => $this->user->id,
                'transaction_category_id' => $transactionCategory->id,
            ])
            ->toArray();

        $response1 = $this->actingAs($this->user)->post(
            route('transactions.store'),
            $transactionStoreData
        );
        $transaction = Transaction::firstOrFail();
        $response2 = $this->actingAs($this->user)->delete(
            route('transactions.destroy', $transaction)
        );

        $response1->assertStatus(302);
        $response2->assertStatus(302);
        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
