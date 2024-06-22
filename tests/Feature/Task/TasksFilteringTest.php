<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

class TasksFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testTasksIndexScreenFiltersTasksCorrectly()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $taskCategory = TaskCategory::factory()->create();
        $includedTasks = Task::factory()
            ->count(5)
            ->create([
                'task_category_id' => $taskCategory->id,
                'user_id' => $user->id,
                'status' => $this->faker->randomElement([
                    'pending',
                    'completed',
                ]),
            ]);
        $excludedTasks = Task::factory()
            ->count(5)
            ->create([
                'task_category_id' => $taskCategory->id,
                'user_id' => $user->id,
                'status' => 'failed',
            ]);

        $response = $this->actingAs($user)->get(
            route('tasks.index', [
                'filterByStatus' => ['pending', 'completed'],
            ])
        );

        $response->assertStatus(200);
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
    }
}
