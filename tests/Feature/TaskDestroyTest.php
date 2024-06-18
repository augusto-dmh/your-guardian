<?php

use App\Http\Requests\Task\TaskStoreRequest;
use Faker\Factory;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

test('HandleTaskCache successfully working on updating a task', function () {
    $user = User::factory()->create();
    Auth::login($user);
    $taskCategory = TaskCategory::factory()->create();
    $taskStoreData = Task::factory()->make([
        'user_id' => $user->id,
        'task_category_id' => $taskCategory->id,
    ]);

    $response1 = $this->actingAs($user)->post(
        route('tasks.store'),
        $taskStoreData->toArray()
    );
    $task = Task::first();
    $response2 = $this->actingAs($user)->delete(route('tasks.destroy', $task));

    $response1->assertStatus(302);
    $response2->assertStatus(302);
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    $this->assertNull(Cache::get("user_{$task->user_id}_next_task_due"));
});
