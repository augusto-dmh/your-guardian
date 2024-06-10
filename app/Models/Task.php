<?php

namespace App\Models;

use App\Models\User;
use App\Models\TaskCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'user_id',
        'task_category_id',
        'title',
        'description',
        'due_date',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($task) {
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
        });

        self::updated(function ($task) {
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
        });

        self::deleted(function ($task) {
            if (
                $task->due_date->isFuture() &&
                $task->status == 'pending' &&
                Cache::get("user_{$task->user_id}_next_task_due") ==
                    $task->due_date
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
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskCategory()
    {
        return $this->belongsTo(TaskCategory::class);
    }
}
