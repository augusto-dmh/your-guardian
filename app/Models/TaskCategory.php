<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskCategory extends Model
{
    use HasFactory;

    protected $table = 'task_categories';

    protected $fillable = [
        'name',
    ];

    public static $rules = [
        'name' => 'required|string|max:255',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
