<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Task;
use App\Helpers\EnumHelper;
use App\Models\TaskCategory;
use Illuminate\Http\Request;
use App\QueryOptions\Sort\DueDate;
use App\Services\TaskFieldService;
use App\QueryOptions\Filter\Status;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Requests\Task\TaskShowRequest;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskDeleteRequest;
use App\Http\Requests\Task\TaskUpdateRequest;

/**
 * @see \App\Observers\TaskObserver
 */
class TaskController extends Controller
{
    public $taskFieldService;

    public function __construct(TaskFieldService $taskFieldService)
    {
        $this->taskFieldService = $taskFieldService;
    }

    public function store(TaskStoreRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $sortFields = $this->taskFieldService->getSortFields();
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

        $filterFields = $this->taskFieldService->getFilterFields();

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
        $textFields = $this->taskFieldService->getTextFields($task);
        $selectFields = $this->taskFieldService->getSelectFields($task);

        return view('tasks.edit', [
            'task' => $task,
            'textFields' => $textFields,
            'selectFields' => $selectFields,
        ]);
    }
}
