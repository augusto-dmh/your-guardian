<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Faker\Factory;

class TaskDestroyTest extends TestCase
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

    public function testHandleTaskCacheSuccessfullyWorkingOnDeletingATask()
    {
        $taskCategory = TaskCategory::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'task_category_id' => $taskCategory->id,
        ]);
        $taskData = $task->toArray();

        $response1 = $this->actingAs($this->user)->post(
            route('tasks.store'),
            $taskData
        );
        $response2 = $this->actingAs($this->user)->delete(
            route('tasks.destroy', $task)
        );

        $response1->assertStatus(302);
        $response2->assertStatus(302);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $this->assertNull(Cache::get("user_{$task->user_id}_next_task_due"));
    }
}
