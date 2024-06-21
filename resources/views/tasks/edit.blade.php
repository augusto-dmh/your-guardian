<x-layout>
    <div class="form-wrapper">
        <div class="flex justify-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-32 h-32" />
            </a>
        </div>

        <form action="{{ route('tasks.update', $task) }}" method="post" class="form-main">
            @csrf
            @method('PUT')

            <fieldset>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" placeholder="Title" value="{{ old('title', $task->title) }}">
                    @error('title')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="due_date">Due date:</label>
                    <input type="date" name="due_date" id="due_date"
                        value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}">
                    @error('due_date')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="task_category_id">Category:</label>
                    <select name="task_category_id" id="task_category_id">
                        <option value="">Select a category</option>

                        @foreach ($taskCategories as $taskCategory)
                            <option value="{{ $taskCategory->id }}"
                                {{ old('task_category_id', $task->taskCategory->id) === $taskCategory->id ? 'selected' : '' }}>
                                {{ ucFirst($taskCategory->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                        <option value="failed" {{ old('status', $task->status) === 'failed' ? 'selected' : '' }}>Failed
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <div class="form-group">
                <button>Edit Task</button>
            </div>
        </form>
    </div>
</x-layout>

