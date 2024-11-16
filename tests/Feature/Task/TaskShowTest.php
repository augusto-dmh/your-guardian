<?php

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Faker::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('task successfully showed', function () {
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
});