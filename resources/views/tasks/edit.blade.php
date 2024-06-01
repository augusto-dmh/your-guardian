<x-layout>
    <h1>Edit Task</h1>

    <form action="{{ route('tasks.update', $task) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" placeholder="Title" value="{{ $task->title }}">
            @error('title')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <label for="due_date">Due date:</label>
            <input type="date" name="due_date" id="due_date" value="{{ $task->due_date }}">
            @error('due_date')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <select name="category" id="category">
                @foreach ($taskCategories as $taskCategory)
                    <option value="{{ $taskCategory->name }}"
                        {{ $taskCategory->name === $task->taskCategory->name ? 'selected' : '' }}>
                        {{ ucFirst($taskCategory->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ $task->status === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
            @error('description')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <button>Edit Task</button>
        </div>
    </form>
</x-layout>

