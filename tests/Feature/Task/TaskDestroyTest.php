<?php

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Faker\Factory;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('handle task cache successfully working on deleting atask', function () {
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
    expect(Cache::get("user_{$task->user_id}_next_task_due"))->toBeNull();
});