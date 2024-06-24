<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;

class TaskShowTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    public function testTaskSuccessfullyShowed()
    {
        $taskCategory = TaskCategory::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'task_category_id' => $taskCategory->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('tasks.show', ['task' => $task->id])
        );

        $response->assertStatus(200);
        $response->assertViewHas('task', $task);
    }
}
