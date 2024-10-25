<x-layout>
    <div class="form-wrapper">
        <div class="flex justify-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-32 h-32" />
            </a>
        </div>

        <form action="{{ route('tasks.store') }}" method="post" class="form-main">
            @csrf

            <fieldset>
                <div class="form-group">
                    <label for="title">{{ __('Title') }}:</label>
                    <input type="text" name="title" value="{{ old('title') }}">
                    @error('title')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="due_date">{{ __('Due date') }}:</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}">
                    @error('due_date')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="task_category_id">{{ __('Category') }}</label>
                    <select name="task_category_id" id="category">
                        <option value="">{{ __('Select a category') }}</option>

                        @foreach ($taskCategories as $taskCategory)
                            <option value="{{ $taskCategory->id }}"
                                {{ old('task_category_id') === $taskCategory->id ? 'selected' : '' }}>
                                {{ ucfirst($taskCategory->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">{{ __('Status') }}:</label>
                    <select name="status" id="status">
                        @foreach($taskStatuses as $taskStatus)
                            <option value="{{ $taskStatus }}" {{ old('status') === $taskStatus ? 'selected' : '' }}>
                                {{ __(ucfirst($taskStatus)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('Description') }}:</label>
                    <textarea id="description" name="description" rows="4" cols="50">{{ old('description') }}</textarea>
                    @error('description')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <div class="form-group">
                <button>{{ __('Create Task') }}</button>
            </div>
        </form>
    </div>
</x-layout>
