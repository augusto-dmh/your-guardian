<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class BillShowTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    public function testBillSuccessfullyShowed()
    {
        $user = User::factory()->create();
        $bill = Bill::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(
            route('bills.show', ['bill' => $bill->id])
        );

        $response->assertStatus(200);
        $response->assertViewHas('bill', $bill);
    }
}
