<?php

use App\Http\Controllers\TaskController;

Route::get('/tasks/create', [TaskController::class, 'create'])
    ->middleware('auth')
    ->name('tasks.create');

Route::post('/tasks', [TaskController::class, 'store'])
    ->middleware('auth')
    ->name('tasks.store');

Route::get('/tasks', [TaskController::class, 'index'])
    ->middleware('auth')
    ->name('tasks.index');

Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])
    ->middleware(['auth', 'store.previous.url.not.edit'])
    ->name('tasks.edit');

Route::get('/tasks/{task}', [TaskController::class, 'show'])
    ->middleware('auth')
    ->name('tasks.show');

Route::put('/tasks/{task}', [TaskController::class, 'update'])
    ->middleware('auth')
    ->name('tasks.update');

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
    ->middleware('auth')
    ->name('tasks.destroy');
