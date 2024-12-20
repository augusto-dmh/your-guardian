<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use Auth;
use App\Models\Task;
use App\Models\TaskCategory;
use App\QueryOptions\Sort\DueDate;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Task\TaskShowRequest;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskDeleteRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use Illuminate\Http\Request;

/**
 * @see \App\Observers\TaskObserver
 */
class TaskController extends Controller
{
    public function store(TaskStoreRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $sortFields = ['Amount', 'Due Date'];
        $searchTerm = $request->input('searchTerm');

        $query = Auth::user()->tasks()->getQuery();

        $query = Pipeline::send($query)
            ->through([DueDate::class, Status::class])
            ->thenReturn();

        $query->when($searchTerm, function ($query, $searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query
                    ->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });

            $query->orderByRaw(
                "
                        CASE
                            WHEN title LIKE ? AND description LIKE ? THEN 1
                            WHEN title LIKE ? THEN 2
                            WHEN description LIKE ? THEN 3
                            ELSE 4
                        END
                        ",
                [
                    "%$searchTerm%",
                    "%$searchTerm%",
                    "%$searchTerm%",
                    "%$searchTerm%",
                ]
            );
        });

        $tasks = $query->paginate(10);

        $taskStatuses = EnumHelper::getEnumValues('tasks', 'status');

        $filterFields = [
            ['name' => 'Status', 'values' => $taskStatuses],
        ];

        return view('tasks.index', compact('tasks', 'searchTerm', 'sortFields', 'filterFields'));
    }

    public function show(TaskShowRequest $request, Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $validatedData = $request->validated();

        $task->update($validatedData);

        return redirect()->back();
    }

    public function destroy(TaskDeleteRequest $request, Task $task)
    {
        $task->delete();

        if (preg_match('/\/tasks\/\d+$/', URL::previous())) {
            return redirect()->route('tasks.index');
        }
        return redirect()->back();
    }

    public function create()
    {
        $taskCategories = TaskCategory::all();
        $taskStatuses = EnumHelper::getEnumValues('tasks', 'status');

        return view('tasks.create', compact('taskCategories', 'taskStatuses'));
    }

    public function edit(Task $task)
    {
        $textFields = [
            ['name' => 'title', 'exhibitionName' => 'Title', 'value' => $task->title],
            ['name' => 'description', 'exhibitionName' => 'Description', 'value' => $task->description],
        ];

        $selectFields = [
            [
                'name' => 'status',
                'exhibitionName' => 'Status',
                'value' => $task->status,
                'options' => array_map(function ($status) {
                    return ['value' => $status, 'label' => ucfirst($status)];
                }, EnumHelper::getEnumValues('tasks', 'status'))
            ],
            [
                'name' => 'task_category_id',
                'exhibitionName' => 'Category',
                'value' => $task->taskCategory?->id,
                'options' => TaskCategory::all()->map(function ($category) {
                    return ['value' => $category->id, 'label' => $category->name];
                })->toArray()
            ]
        ];

        return view('tasks.edit', [
            'task' => $task,
            'textFields' => $textFields,
            'selectFields' => $selectFields,
        ]);
    }
}
