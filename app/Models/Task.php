<?php

namespace App\Models;

use App\CacheHandlers\TaskCacheHandler;
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

        self::created(function ($task) {
            TaskCacheHandler::handleCreatedTask($task);
        });

        self::updated(function ($task) {
            TaskCacheHandler::handleUpdatedTask($task);
        });

        self::deleted(function ($task) {
            TaskCacheHandler::handleDeletedTask($task);
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
