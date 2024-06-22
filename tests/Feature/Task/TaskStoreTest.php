<?php

use Faker\Generator as Faker;
use App\Models\Task;
use App\Models\User;
use App\Events\TaskCreated;
use App\Models\TaskCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\TestCase;

class TaskStoreTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = app(Faker::class);
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    public function testTaskSuccessfullyStored()
    {
        $taskCategory = TaskCategory::factory()->create();

        $response = $this->actingAs($this->user)->post(route('tasks.store'), [
            'task_category_id' => $taskCategory->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ]);

        $task = Task::latest()->first();

        $response->assertStatus(302);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    public function testHandleTaskCacheSuccessfullyWorkingOnStoringATask()
    {
        $taskCategory = TaskCategory::factory()->create();

        $response1 = $this->actingAs($this->user)->post(route('tasks.store'), [
            'task_category_id' => $taskCategory->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ]);
        $response2 = $this->actingAs($this->user)->post(route('tasks.store'), [
            'task_category_id' => $taskCategory->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'due_date' => now()->addDays(2)->toDateString(),
            'status' => 'pending',
        ]);
        $taskCreatedOnSecondRequest = $this->user
            ->tasks()
            ->latest('id')
            ->first();

        $this->assertEquals(
            Cache::get("user_{$this->user->id}_next_task_due")->format('Y-m-d'),
            $taskCreatedOnSecondRequest->due_date->format('Y-m-d')
        );
    }
}
