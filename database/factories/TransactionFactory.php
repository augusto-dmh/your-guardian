<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // First, determine the type of transaction
        $type = $this->faker->randomElement(['income', 'expense']);

        // Based on the type, get the corresponding category IDs (considering the TransactionCategorySeeder has already run)
        $categoryIds = TransactionCategory::where('transaction_type', $type)
            ->pluck('id')
            ->toArray();

        // If no categories found for the selected type, take the category IDs from the other type (case where categories have been created for only one type)
        if (empty($categoryIds)) {
            $type = $type === 'income' ? 'expense' : 'income';
            $categoryIds = TransactionCategory::where('transaction_type', $type)
                ->pluck('id')
                ->toArray();
        }

        // Randomly pick one category ID from the list
        $randomCategoryId = $this->faker->randomElement($categoryIds);

        // Generate a random amount
        $amount = $this->faker->numberBetween(1, 5000);

        // Ensure the amount is consistent with the type
        $amount = $type === 'income' ? abs($amount) : -abs($amount);

        return [
            'transaction_category_id' => $randomCategoryId,
            'amount' => $amount,
            'type' => $type,
            'title' => $this->faker->sentence(random_int(1, 3)),
            'description' => $this->faker->sentence,
            'created_at' => $this->faker->date(),
        ];
    }
}
