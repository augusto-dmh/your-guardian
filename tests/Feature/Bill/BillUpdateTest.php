<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Bill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Faker\Factory as Faker;

class BillUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    public function testBillSuccessfullyUpdated()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $bill = Bill::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);
        $newDueDate = formatDate(now()->addDays(2));
        $response = $this->actingAs($user)->put(route('bills.update', $bill), [
            'due_date' => $newDueDate,
        ]);
        $bill->refresh();

        $this->assertEquals($newDueDate, formatDate($bill->due_date));
    }

    public function testHandleBillCacheSuccessfullyWorkingOnUpdatingABill()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $bill = Bill::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);
        $newDueDate = formatDate(now()->addDays(2));
        $response = $this->actingAs($user)->put(route('bills.update', $bill), [
            'due_date' => $newDueDate,
        ]);
        $bill->refresh();

        $this->assertTrue(
            formatDate(Cache::get("user_{$bill->user_id}_next_bill_due")) ==
                formatDate($bill->due_date)
        );
    }
}
