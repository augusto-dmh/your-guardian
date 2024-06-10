<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Http\Requests\TaskRequest;
use App\QueryOptions\Sort\DueDate;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Pipeline;

class TaskController extends Controller
{
    public function store(TaskRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

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

        return redirect()->back();
    }

    public function index()
    {
        $query = Auth::user()->tasks()->getQuery();

        $tasks = Pipeline::send($query)
            ->through([DueDate::class, Status::class])
            ->thenReturn()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();

        $task->update($validatedData);

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

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

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

        return redirect()->back();
    }

    public function create()
    {
        $taskCategories = TaskCategory::all();

        return view('tasks.create', compact('taskCategories'));
    }

    public function edit(Task $task)
    {
        $taskCategories = TaskCategory::all();

        return view('tasks.edit', compact('task', 'taskCategories'));
    }
}
