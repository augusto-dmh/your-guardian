<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Task;
use App\Models\TaskCategory;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        Auth::user()
            ->tasks()
            ->create([
                'name' => $request->name,
                'title' => $request->title,
                'status' => $request->status,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'task_category_id' => TaskCategory::where([
                    'name' => $request->category,
                ])->first()->id,
            ]);

        return redirect()->back();
    }

    public function index()
    {
        $tasks = Auth::user()->tasks()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return redirect()->back();
    }

    public function destroy(Task $task)
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
