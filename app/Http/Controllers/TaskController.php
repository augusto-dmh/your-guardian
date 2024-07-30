<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskDeleteRequest;
use App\Http\Requests\Task\TaskShowRequest;
use Auth;
use App\Models\Task;
use App\Models\TaskCategory;
use App\QueryOptions\Sort\DueDate;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;

class TaskController extends Controller
{
    public function store(TaskStoreRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

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

    public function show(TaskShowRequest $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $validatedData = $request->validated();

        $task->update($validatedData);

        return redirect()->back();
    }

    public function destroy(TaskDeleteRequest $task)
    {
        $task->delete();

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
