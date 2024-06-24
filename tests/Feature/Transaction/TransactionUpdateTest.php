<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\Bill;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionUpdateTest extends TestCase
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

    public function testTransactionSuccessfullyUpdated()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $transactionUpdateData = [
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'description' => $this->faker->paragraph,
            'type' => $transaction->type,
        ];
        $response = $this->actingAs($this->user)->put(
            route('transactions.update', $transaction),
            $transactionUpdateData
        );
        $expectedData = Arr::except($transactionUpdateData, ['amount']);
        $expectedData['amount'] =
            $transactionUpdateData['type'] === 'income'
                ? abs($transactionUpdateData['amount'])
                : -1 * abs($transactionUpdateData['amount']);

        $response->assertStatus(302);
        $this->assertDatabaseHas('transactions', $expectedData);
    }
}
