<x-layout>
    <h1>Create Task</h1>

    <form action="{{ route('tasks.store') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" placeholder="Title">
        </div>
        <div class="form-group">
            <label for="due_date">Due date:</label>
            <input type="date" name="due_date" id="due_date">
        </div>
        <div class="form-group">
            <select name="category" id="category">
                @foreach ($taskCategories as $taskCategory)
                    <option value="{{ $taskCategory->name }}">{{ ucFirst($taskCategory->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
        </div>

        <div class="form-group">
            <button>Create Task</button>
        </div>
    </form>
</x-layout>

