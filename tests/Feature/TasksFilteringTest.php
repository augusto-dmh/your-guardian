<?php

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

test('tasks.index screen filters tasks correctly', function () {
    $faker = Factory::create();

    $user = User::factory()->create();
    Auth::login($user);

    $taskCategory = TaskCategory::factory()->create();
    $includedTasks = Task::factory(5)->create([
        'task_category_id' => $taskCategory->id,
        'user_id' => $user->id,
        'status' => $faker->randomElement(['pending', 'completed']),
    ]);
    $excludedTasks = Task::factory(5)->create([
        'task_category_id' => $taskCategory->id,
        'user_id' => $user->id,
        'status' => 'failed',
    ]);

    $response = $this->actingAs($user)->get(
        route('tasks.index', [
            'filterByStatus' => $faker->randomElement([
                'pending',
                ['pending', 'completed'],
            ]),
        ])
    );

    $response->assertViewHas('tasks', function ($viewTasks) use (
        $includedTasks,
        $excludedTasks
    ) {
        foreach ($includedTasks as $task) {
            if (!$viewTasks->contains($task)) {
                return false;
            }
        }

        foreach ($excludedTasks as $task) {
            if ($viewTasks->contains($task)) {
                return false;
            }
        }

        return true;
    });
});
