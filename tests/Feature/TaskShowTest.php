<?php

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('task successfully showed', function () {
    $user = User::factory()->create();
    $taskCategory = TaskCategory::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'task_category_id' => $taskCategory,
    ]);
    Auth::login($user);

    $response = $this->actingAs($user)->get(
        route('tasks.show', compact('task'))
    );

    $response->assertStatus(200);
    $response->assertViewHas('task', $task);
});
