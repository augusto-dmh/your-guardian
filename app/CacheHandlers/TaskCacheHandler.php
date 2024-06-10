<?php

namespace App\CacheHandlers;

use Illuminate\Support\Facades\Cache;

class TaskCacheHandler
{
    public static function handleCreatedTask($task)
    {
        if ($task->due_date->isFuture() && $task->status == 'pending') {
            $nextPendingTaskDueDate = Cache::get(
                "user_{$task->user_id}_next_task_due"
            );

            if (
                !$nextPendingTaskDueDate ||
                $task->due_date->format('Y-m-d') < $nextPendingTaskDueDate
            ) {
                Cache::put(
                    "user_{$task->user_id}_next_task_due",
                    $task->due_date->format('Y-m-d'),
                    60
                );
            }
        }
    }

    public static function handleUpdatedTask($task)
    {
        if ($task->due_date->isFuture() && $task->status == 'pending') {
            $nextPendingTaskDueDate =
                Cache::get("user_{$task->user_id}_next_task_due") ??
                ($task->user
                    ->tasks()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()
                    ?->due_date->format('Y-m-d') ??
                    'none');

            $task->due_date->format('Y-m-d') < $nextPendingTaskDueDate
                ? Cache::put(
                    "user_{$task->user_id}_next_task_due",
                    $task->due_date->format('Y-m-d'),
                    60
                )
                : Cache::add(
                    "user_{$task->user_id}_next_task_due",
                    $nextPendingTaskDueDate,
                    60
                );
        }
    }

    public static function handleDeletedTask($task)
    {
        if (
            $task->due_date->isFuture() &&
            $task->status == 'pending' &&
            Cache::get("user_{$task->user_id}_next_task_due") == $task->due_date
        ) {
            Cache::put(
                "user_{$task->user_id}_next_task_due",
                $task->user
                    ->tasks()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()
                    ?->due_date->format('Y-m-d') ?? 'none',
                60
            );
        }
    }
}
