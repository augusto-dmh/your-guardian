<?php

namespace Database\Factories;

use App\Models\TaskCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskCategoryFactory extends Factory
{
    protected $model = TaskCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
