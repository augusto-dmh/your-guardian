<x-layout>
    <h2>Tasks</h2>
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
            @foreach (auth()->user()->tasks as $task)
                <tr>
                    <td>{{ $task->taskCategory->name }}</td>
                    <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                    <td>{{ Str::limit($task->description, 20, '...') }}</td>
                    <td>{{ $task->created_at->format('m-d-Y') }}</td>
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

