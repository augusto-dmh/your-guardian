<x-layout>
    <h2>Tasks</h2>

    <form method="GET" action="{{ route('tasks.index') }}">
        <div>
            <h5>Sort by:</h5>
            <div class="form-group">
                <p>Due date:</p>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByDueDate" id="sortByDueDateAsc" value="asc"
                        {{ request('sortByDueDate') == 'asc' ? 'checked' : '' }}>
                    <label for="sortByDueDateAsc">Ascending</label>
                </div>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByDueDate" id="sortByDueDateDesc" value="desc"
                        {{ request('sortByDueDate') == 'desc' ? 'checked' : '' }}>
                    <label for="sortByDueDateDesc">Descending</label>
                </div>
            </div>
        </div>

        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->taskCategory?->name ?? 'none' }}</td>
                    <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                    <td>{{ Str::limit($task->description, 20, '...') }}</td>
                    <td>{{ $task->due_date->format('m-d-Y') }}</td>
                    <td>
                        <form action="{{ route('tasks.destroy', ['task' => $task]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('tasks.edit', ['task' => $task]) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>

