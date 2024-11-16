<?php

use Faker\Generator as Faker;
use App\Models\Task;
use App\Models\User;
use App\Events\TaskCreated;
use App\Models\TaskCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->faker = app(Faker::class);
    $this->user = User::factory()->create();
    Auth::login($this->user);
});
test('task successfully stored', function () {
    $taskCategory = TaskCategory::factory()->create();
    $taskData = [
        'task_category_id' => $taskCategory->id,
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ];

    $response = $this->actingAs($this->user)->post(
        route('tasks.store'),
        $taskData
    );

    $response->assertStatus(302);
    $this->assertDatabaseHas('tasks', $taskData);
});
test('handle task cache successfully working on storing atask', function () {
    $taskCategory = TaskCategory::factory()->create();

    $response1 = $this->actingAs($this->user)->post(route('tasks.store'), [
        'task_category_id' => $taskCategory->id,
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'amount' => $this->faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(3)->toDateString(),
        'status' => 'pending',
    ]);
    $response2 = $this->actingAs($this->user)->post(route('tasks.store'), [
        'task_category_id' => $taskCategory->id,
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'amount' => $this->faker->randomFloat(2, 0, 1000),
        'due_date' => now()->addDays(2)->toDateString(),
        'status' => 'pending',
    ]);
    $taskCreatedOnSecondRequest = $this->user
        ->tasks()
        ->latest('id')
        ->first();

    expect(formatDate($taskCreatedOnSecondRequest->due_date))->toEqual(formatDate(Cache::get("user_{$this->user->id}_next_task_due")));
});
