<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

class TransactionCreateTest extends TestCase
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

    public function testTransactionCreateViewSuccessfullyShowed()
    {
        $response = $this->actingAs($this->user)->get(
            route('transactions.create')
        );

        $response->assertStatus(200);
        $response->assertViewIs('transactions.create');
        $response->assertSee('form');
    }
}
