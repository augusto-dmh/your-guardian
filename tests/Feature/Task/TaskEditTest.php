<?php

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Faker\Factory;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('task edit view successfully showed', function () {
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
});