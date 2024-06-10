<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
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

        TaskCreated::dispatch($task);

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

        TaskUpdated::dispatch($task);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        TaskDeleted::dispatch($task);

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
