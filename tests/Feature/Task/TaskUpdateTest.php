<?php

use Faker\Factory;
use App\Models\Task;
use App\Models\User;
use App\Events\TaskUpdated;
use App\Models\TaskCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = Factory::create();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

test('task successfully updated', function () {
    $taskCategory = TaskCategory::factory()->create();
    $task = Task::factory()->create([
        'task_category_id' => $taskCategory->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = formatDate(now()->addDays(2));
    $response = $this->actingAs($this->user)->put(
        route('tasks.update', $task),
        [
            'due_date' => $newDueDate,
        ]
    );
    $task->refresh();

    expect(formatDate($task->due_date))->toEqual($newDueDate);
});

test('handle task cache successfully working on updating atask', function () {
    $taskCategory = TaskCategory::factory()->create();
    $task = Task::factory()->create([
        'task_category_id' => $taskCategory->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    $newDueDate = formatDate(now()->addDays(2));
    $response = $this->actingAs($this->user)->put(
        route('tasks.update', $task),
        [
            'due_date' => $newDueDate,
        ]
    );
    $task->refresh();

    expect(formatDate(Cache::get("user_{$task->user_id}_next_task_due")) ==
        $newDueDate)->toBeTrue();
});