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
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 0, 10000),
            'due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement([
                'pending',
                'paid',
                'overdue',
            ]),
            'paid_at' => $this->faker->randomElement([
                null,
                $this->faker->date(),
            ]),
        ];
    }
}
