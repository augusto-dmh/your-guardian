<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\EventInterface;
use App\Events\TaskUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTaskCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public static function handle(TaskCreated|TaskUpdated|TaskDeleted $event)
    {
        $task = $event->getEntity();
        if ($task->wasRecentlyCreated) {
            self::handleCreatedTask($event);
        } elseif ($task->wasChanged()) {
            self::handleUpdatedTask($event);
        } elseif (!$task->exists) {
            self::handleDeletedTask($event);
        }
    }

    private static function handleCreatedTask(TaskCreated $event)
    {
        $task = $event->getEntity();

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

    private static function handleUpdatedTask(TaskUpdated $event)
    {
        $task = $event->getEntity();
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

            $task->due_date < $nextPendingTaskDueDate
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

    private static function handleDeletedTask(TaskDeleted $event)
    {
        $task = $event->getEntity();

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
