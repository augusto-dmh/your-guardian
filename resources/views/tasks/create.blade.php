<x-layout>
    <div class="form-wrapper">
        <x-your-guardian-logo-1 />

        <form action="{{ route('tasks.store') }}" method="post" class="form-main">
            @csrf

            <fieldset>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" placeholder="Title" value="{{ old('title') }}">
                    @error('title')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="due_date">Due date:</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}">
                    @error('due_date')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="task_category_id">Category</label>
                    <select name="task_category_id" id="category">
                        @foreach ($taskCategories as $taskCategory)
                            <option value="{{ $taskCategory->id }}"
                                {{ old('task_category_id') === $taskCategory->id ? 'selected' : '' }}>
                                {{ ucFirst($taskCategory->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="failed" {{ old('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50">{{ old('description') }}</textarea>
                    @error('description')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <div class="form-group">
                <button>Create Task</button>
            </div>
        </form>
    </div>
</x-layout>

