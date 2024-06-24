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

        return [
            'transaction_category_id' => $randomCategoryId,
            'amount' => $this->faker->numberBetween(1, 1000),
            'type' => $type,
            'description' => $this->faker->sentence,
        ];
    }
}
