<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Bill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Faker\Factory as Faker;

class BillDestroyTest extends TestCase
{
    use RefreshDatabase;

    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    public function testBillSuccessfullyDestroyed()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $bill = Bill::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_date' => now()->addDay()->toDateString(),
        ]);
        $response = $this->actingAs($user)->delete(
            route('bills.destroy', $bill)
        );

        $response->assertStatus(302);
        $this->assertDatabaseMissing('bills', ['id' => $bill->id]);
    }

    public function testHandleBillCacheSuccessfullyWorkingOnUpdatingABill()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $billData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'due_date' => now()->addDays()->toDateString(),
            'status' => 'pending',
        ];
        $this->actingAs($user)->post(route('bills.store'), $billData);
        $bill = Bill::latest()->first();
        $this->actingAs($user)->delete(route('bills.destroy', $bill));

        $this->assertNull(Cache::get("user_{$bill->user_id}_next_bill_due"));
    }
}
