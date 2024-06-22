<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class BillCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    public function testBillCreateViewSuccessfullyShowed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('bills.create'));

        $response->assertStatus(200);
        $response->assertViewIs('bills.create');
        $response->assertSee('form');
    }
}
