<?php

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('task edit view successfully showed', function () {
    $user = User::factory()->create();
    Auth::login($user);
    $taskCategory = TaskCategory::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'task_category_id' => $taskCategory->id,
    ]);

    $response = $this->actingAs($user)->get(
        route('tasks.edit', compact('task'))
    );

    $response->assertStatus(200);
    $response->assertViewIs('tasks.edit');
    $response->assertViewHas('task', $task);
    $response->assertSee('form');
});
