<h2>Task</h2>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Category</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $task->title }}</td>
            <td>{{ $task->description }}</td>
            <td>{{ $task->taskCategory?->name ?? 'none' }}</td>
            <td>{{ $task->due_date->format('m-d-Y') }}</td>
            <td>{{ $task->status }}</td>
        </tr>
    </tbody>
</table>

