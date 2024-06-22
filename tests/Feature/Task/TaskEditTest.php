<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;

class TaskEditTest extends TestCase
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

    public function testTaskEditViewSuccessfullyShowed()
    {
        $taskCategory = TaskCategory::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'task_category_id' => $taskCategory->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('tasks.edit', $task)
        );

        $response->assertStatus(200);
        $response->assertViewIs('tasks.edit');
        $response->assertViewHas('task', $task);
        $response->assertSee('form');
    }
}
