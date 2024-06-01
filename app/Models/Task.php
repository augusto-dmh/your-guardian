<?php

namespace App\Models;

use App\Models\User;
use App\Models\TaskCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'task_category_id',
        'title',
        'description',
        'due_date',
        'status',
    ];

    public static $rules = [
        'task_category_id' => 'exists:task_categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:65535',
        'due_date' => 'required|date',
        'status' => 'string|in:pending,completed,failed',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'due_date' => 'datetime',
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
