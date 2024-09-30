<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="p-6 m-auto rounded-sm shadow-inner form-wrapper h-fit w-fit">
        <div class="w-20 h-20 m-auto">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-full h-full" />
            </a>
        </div>

        <form action="{{ route('tasks.update', $task) }}" method="post">
            @csrf
            @method('PUT')

            <fieldset class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label for="title" class="cursor-pointer text-secondary-txt">{{ __('Title') }}:</label>
                    <input type="text" name="title" placeholder="Title" value="{{ old('title', $task->title) }}"
                        id="title"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                    @error('title')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label for="due_date" class="cursor-pointer text-secondary-txt">{{ __('Due Date') }}:</label>
                    <input type="date" name="due_date" id="due_date"
                        value="{{ old('due_date', formatDate($task->due_date)) }}"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                    @error('due_date')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label for="task_category_id"
                        class="cursor-pointer text-secondary-txt">{{ __('Category') }}:</label>
                    <select name="task_category_id" id="task_category_id"
                        class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option value="">{{ __('Select a Category') }}</option>
                        @foreach ($taskCategories as $taskCategory)
                            <option value="{{ $taskCategory->id }}"
                                {{ old('task_category_id', $task->taskCategory->id) === $taskCategory->id ? 'selected' : '' }}>
                                {{ ucfirst($taskCategory->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="status" class="cursor-pointer text-secondary-txt">{{ __('Status') }}:</label>
                    <select name="status" id="status"
                        class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                        <option value="failed" {{ old('status', $task->status) === 'failed' ? 'selected' : '' }}>
                            Failed
                        </option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="description" class="cursor-pointer text-secondary-txt">{{ __('Description') }}:</label>
                    <textarea id="description" name="description" rows="4" cols="50"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <div class="flex gap-3 mt-8">
                <button type="submit"
                    class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Update') }}</button>
                <a href="{{ route('bills.index') }}"
                    class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
</x-app-layout>
