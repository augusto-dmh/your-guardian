<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Faker\Factory;

class BillStoreTest extends TestCase
{
    use RefreshDatabase;

    public function testBillSuccessfullyStored()
    {
        $faker = Factory::create();
        $user = User::factory()->create();
        Auth::login($user);
        $billData = [
            'title' => $faker->sentence,
            'description' => $faker->paragraph,
            'amount' => $faker->randomFloat(2, 0, 1000),
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ];

        $response = $this->actingAs($user)->post(
            route('bills.store'),
            $billData
        );

        $response->assertStatus(302); // redirected
        $this->assertDatabaseHas('bills', $billData);
    }

    public function testHandleBillCacheSuccessfullyWorkingOnStoringABill()
    {
        $faker = Factory::create();
        $user = User::factory()->create();
        Auth::login($user);

        $this->actingAs($user)->post(route('bills.store'), [
            'title' => $faker->sentence,
            'description' => $faker->paragraph,
            'amount' => $faker->randomFloat(2, 0, 1000),
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ]);
        $this->actingAs($user)->post(route('bills.store'), [
            'title' => $faker->sentence,
            'description' => $faker->paragraph,
            'amount' => $faker->randomFloat(2, 0, 1000),
            'due_date' => now()->addDays(2)->toDateString(),
            'status' => 'pending',
        ]);
        $BillCreatedOnSecondRequest = $user->bills()->latest('id')->first();

        $this->assertEquals(
            formatDate(Cache::get("user_{$user->id}_next_bill_due")),
            formatDate($BillCreatedOnSecondRequest->due_date)
        );
    }
}
