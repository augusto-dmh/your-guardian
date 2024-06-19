<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Cache;

class TaskObserver
{
    public function created(Task $task): void
    {
        if ($task->due_date->isFuture() && $task->status == 'pending') {
            $nextPendingTaskDueDate = Cache::get(
                "user_{$task->user_id}_next_task_due"
            );

            if (
                !$nextPendingTaskDueDate ||
                $task->due_date < $nextPendingTaskDueDate
            ) {
                Cache::put(
                    "user_{$task->user_id}_next_task_due",
                    $task->due_date,
                    60
                );
            }
        }
    }

    public function updated(Task $task): void
    {
        $task->refresh();

        if ($task->due_date->isFuture() && $task->status == 'pending') {
            $nextPendingTaskDueDate =
                Cache::get("user_{$task->user_id}_next_task_due") ??
                $task->user
                    ->tasks()
                    ->where('due_date', '>=', now())
                    ->where('status', '=', 'pending')
                    ->orderBy('due_date', 'asc')
                    ->first()?->due_date;

            $task->due_date <= $nextPendingTaskDueDate
                ? Cache::put(
                    "user_{$task->user_id}_next_task_due",
                    $task->due_date,
                    60
                )
                : Cache::add(
                    "user_{$task->user_id}_next_task_due",
                    $nextPendingTaskDueDate,
                    60
                );
        }
    }

    public function deleted(Task $task): void
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
                    ->first()?->due_date,
                60
            );
        }
    }
}
