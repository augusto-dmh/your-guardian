<?php

use Faker\Factory;
use Tests\TestCase;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillsFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testBillsIndexScreenFiltersBillsCorrectly()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $includedBills = Bill::factory()
            ->count(5)
            ->create([
                'user_id' => $user->id,
                'status' => $this->faker->randomElement(['pending', 'paid']),
            ]);

        $excludedBills = Bill::factory()
            ->count(5)
            ->create([
                'user_id' => $user->id,
                'status' => 'overdue',
            ]);

        $response = $this->actingAs($user)->get(
            route('bills.index', [
                'filterByStatus' => ['pending', 'paid'],
            ])
        );

        $response->assertViewHas('bills', function ($viewBills) use (
            $includedBills,
            $excludedBills
        ) {
            foreach ($includedBills as $bill) {
                if (!$viewBills->contains($bill)) {
                    return false;
                }
            }

            foreach ($excludedBills as $bill) {
                if ($viewBills->contains($bill)) {
                    return false;
                }
            }

            return true;
        });
    }
}
