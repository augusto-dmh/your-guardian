<?php

namespace App\Models;

use App\Models\User;
use App\Models\TaskCategory;
use App\Observers\TaskObserver;
use Illuminate\Support\Facades\Cache;
use App\CacheHandlers\TaskCacheHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'task_category_id',
        'user_id',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskCategory()
    {
        return $this->belongsTo(TaskCategory::class);
    }
}
