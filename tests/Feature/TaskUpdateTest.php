<?php

use Faker\Factory;
use App\Models\Task;
use App\Models\User;
use App\Events\TaskUpdated;
use App\Models\TaskCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

test('Task successfully updated', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $taskCategory = TaskCategory::factory()->create();
    $task = Task::factory()->create([
        'task_category_id' => $taskCategory->id,
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = now()->addDays(2)->format('Y-m-d');
    $response = $this->actingAs($user)->put(route('tasks.update', $task), [
        'due_date' => $newDueDate,
    ]);
    $task->refresh();

    $this->assertEquals($newDueDate, $task->due_date->format('Y-m-d'));
});

test('HandleTaskCache successfully working on updating a task', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $taskCategory = TaskCategory::factory()->create();
    $task = Task::factory()->create([
        'task_category_id' => $taskCategory->id,
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = now()->addDays(2)->format('Y-m-d');
    $response = $this->actingAs($user)->put(route('tasks.update', $task), [
        'due_date' => $newDueDate,
    ]);
    $task->refresh();

    $this->assertTrue(
        Cache::get("user_{$task->user_id}_next_task_due")->format('Y-m-d') ==
            $task->due_date->format('Y-m-d')
    );
});

test('TaskUpdated event dispatched when task updated', function () {
    Event::fake();
    $user = User::factory()->create();
    Auth::login($user);

    $taskCategory = TaskCategory::factory()->create();
    $task = Task::factory()->create([
        'task_category_id' => $taskCategory->id,
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(2)->toDateString(),
    ]);
    $newDueDate = now()->addDays(3)->format('Y-m-d');
    $response = $this->actingAs($user)->put(route('tasks.update', $task), [
        'due_date' => $newDueDate,
    ]);
    $task->refresh();

    Event::assertDispatched(TaskUpdated::class);
});
