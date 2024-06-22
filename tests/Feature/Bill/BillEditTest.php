<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class BillEditTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    public function testBillEditViewSuccessfullyShowed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $bill = Bill::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('bills.edit', ['bill' => $bill]));

        $response->assertStatus(200);
        $response->assertViewIs('bills.edit');
        $response->assertViewHas('bill', $bill);
        $response->assertSee('form');
    }
}
