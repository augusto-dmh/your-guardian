<?php

use App\Http\Controllers\TaskController;

Route::get('/tasks/create', [TaskController::class, 'create'])
    ->middleware('auth')
    ->name('task.create');

Route::post('/tasks', [TaskController::class, 'store'])
    ->middleware('auth')
    ->name('task.store');

Route::get('/tasks', [TaskController::class, 'index'])
    ->middleware('auth')
    ->name('task.index');

Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])
    ->middleware('auth')
    ->name('task.edit');

Route::get('/tasks/{task}', [TaskController::class, 'show'])
    ->middleware('auth')
    ->name('task.show');

Route::put('/tasks/{task}', [TaskController::class, 'update'])
    ->middleware('auth')
    ->name('task.update');

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
    ->middleware('auth')
    ->name('task.destroy');
