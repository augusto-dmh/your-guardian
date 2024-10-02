<x-app-layout>
    <x-slot name="header"></x-slot>

    <x-edit-form :formAction="route('tasks.update', $task)"
        :model="$task">
        <!-- Task Specific Fields -->

        <!-- Due Date Field -->
        <div class="relative flex flex-col">
            <label for="due_date"
                class="cursor-pointer text-secondary-txt">{{ __('Due date') }}:</label>
            <input type="date"
                name="due_date"
                value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}"
                id="due_date"
                class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 focus:outline-none focus:ring-2 focus:ring-quinary-bg">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none top-6">
                <svg class="w-5 h-5 text-gray-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            @error('due_date')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Task Category Field -->
        <div class="flex flex-col gap-1">
            <label for="task_category_id"
                class="cursor-pointer text-secondary-txt">{{ __('Category') }}:</label>
            <select name="task_category_id"
                id="task_category_id"
                class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                <option value="">{{ __('Select a Category') }}</option>
                @foreach ($taskCategories as $taskCategory)
                    <option value="{{ $taskCategory->id }}"
                        {{ old('task_category_id', $task->taskCategory->id) === $taskCategory->id ? 'selected' : '' }}>
                        {{ ucfirst($taskCategory->name) }}</option>
                @endforeach
            </select>
        </div>
    </x-edit-form>
</x-app-layout>
