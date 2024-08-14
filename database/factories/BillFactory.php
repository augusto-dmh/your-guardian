<?php

namespace Database\Factories;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'paid', 'overdue']);
        $due_date = $this->faker->dateTimeBetween('-10 years');
        $paidAt =
            $status !== 'paid'
                ? null
                : $this->faker->dateTimeBetween('-10 years');

        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 0, 5000),
            'due_date' => $due_date,
            'status' => $status,
            'paid_at' => $paidAt,
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Bill $bill) {
            if ($bill->status === 'paid') {
                $bill->transactions()->create([
                    'user_id' => $bill->user_id,
                    'bill_id' => $bill->id,
                    'amount' => -abs($bill->amount),
                    'type' => 'expense',
                    'title' => $bill->title,
                    'description' => $bill->description,
                    'created_at' => $this->faker->dateTimeBetween('-10 years'),
                ]);
            }
        });
    }
}
