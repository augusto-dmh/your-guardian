<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Factory;
use App\Models\Task;
use App\Models\User;
use App\Events\TaskUpdated;
use App\Models\TaskCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    public function testTaskSuccessfullyUpdated()
    {
        $taskCategory = TaskCategory::factory()->create();
        $task = Task::factory()->create([
            'task_category_id' => $taskCategory->id,
            'user_id' => $this->user->id,
            'status' => 'pending',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);
        $newDueDate = formatDate(now()->addDays(2));
        $response = $this->actingAs($this->user)->put(
            route('tasks.update', $task),
            [
                'due_date' => $newDueDate,
            ]
        );
        $task->refresh();

        $this->assertEquals($newDueDate, formatDate($task->due_date));
    }

    public function testHandleTaskCacheSuccessfullyWorkingOnUpdatingATask()
    {
        $taskCategory = TaskCategory::factory()->create();
        $task = Task::factory()->create([
            'task_category_id' => $taskCategory->id,
            'user_id' => $this->user->id,
            'status' => 'pending',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);
        $newDueDate = formatDate(now()->addDays(2));
        $response = $this->actingAs($this->user)->put(
            route('tasks.update', $task),
            [
                'due_date' => $newDueDate,
            ]
        );
        $task->refresh();

        $this->assertTrue(
            formatDate(Cache::get("user_{$task->user_id}_next_task_due")) ==
                $newDueDate
        );
    }
}
