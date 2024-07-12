<?php

namespace Database\Factories;

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
        $status = $this->faker->randomElement([
            'pending',
            'paid',
            'overdue',
        ]);
        $due_date = $this->faker->date();
        $paidAt = $status !== 'paid' ? null : $this->faker->date(max: $due_date);

        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 0, 10000),
            'due_date' => $due_date,
            'status' => $status,
            'paid_at' => $paidAt,
        ];
    }
}
