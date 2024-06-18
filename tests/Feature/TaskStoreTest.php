<?php

use Faker\Factory;
use App\Models\Task;
use App\Models\User;
use App\Events\BillCreated;
use App\Events\TaskCreated;
use App\Models\TaskCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

test('Task successfully stored', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);
    $taskCategory = TaskCategory::factory()->create();

    $response = $this->actingAs($user)->post(route('tasks.store'), [
        'task_category_id' => $taskCategory->id,
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ]);
    $task = Task::latest()->first();

    $response->assertStatus(302);
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
    ]);
});

test('HandleTaskCache successfully working on storing a task', function () {
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);
    $taskCategory = TaskCategory::factory()->create();

    $response1 = $this->actingAs($user)->post(route('tasks.store'), [
        'task_category_id' => $taskCategory->id,
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ]);
    $response2 = $this->actingAs($user)->post(route('tasks.store'), [
        'task_category_id' => $taskCategory->id,
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(2)->toDateString(),
        'status' => 'pending',
    ]);
    $taskCreatedOnSecondRequest = $user->tasks()->latest('id')->first();

    expect(
        Cache::get("user_{$user->id}_next_task_due")->format('Y-m-d')
    )->toEqual($taskCreatedOnSecondRequest->due_date->format('Y-m-d'));
});

test('TaskCreated event dispatched when task stored', function () {
    Event::fake();
    $faker = Factory::create();
    $user = User::factory()->create();
    Auth::login($user);
    $taskCategory = TaskCategory::factory()->create();

    $this->actingAs($user)->post(route('tasks.store'), [
        'task_category_id' => $taskCategory->id,
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'amount' => $faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ]);

    Event::assertDispatched(TaskCreated::class);
});
