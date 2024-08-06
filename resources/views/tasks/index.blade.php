<x-app-layout>
    <a type="button" href="{{ route('tasks.create') }}"
        class="inline-block px-4 py-1 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Create') }}</a>

    <x-slot name="header">
        <h2 class="text-4xl font-bold text-secondary-txt">{{ __('Tasks') }}</h2>
    </x-slot>

    <form method="GET" action="{{ route('tasks.index') }}">
        <div class="flex items-center gap-8 py-6 m-auto">
            <div class="flex items-center gap-4">
                <h5 class="font-semibold text-primary-txt">{{ __('Sort by:') }}</h5>
                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Due date:') }}</p>
                    <select name="sortByDueDate"
                        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option id="sortByDueDateAsc" value="asc"
                            class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                            {{ request('sortByDueDate') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}</option>
                        <option name="sortByDueDate" id="sortByDueDateDesc" value="desc"
                            class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                            {{ request('sortByDueDate') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <h5 class="font-semibold text-primary-txt">{{ __('Filter by:') }}</h5>
                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Status:') }}</p>
                    <div class="flex flex-col">
                        <label for="input-status-pending"
                            class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                            <input type="checkbox" name="filterByStatus[]" id="input-status-pending" value="pending"
                                class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                {{ is_array(request('filterByStatus')) && in_array('pending', request('filterByStatus')) ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('Pending') }}</span>
                        </label>
                        <label for="input-status-completed"
                            class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                            <input type="checkbox" name="filterByStatus[]" id="input-status-completed" value="completed"
                                class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                {{ is_array(request('filterByStatus')) && in_array('completed', request('filterByStatus')) ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('Completed') }}</span>
                        </label>
                        <label for="input-status-failed"
                            class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                            <input type="checkbox" name="filterByStatus[]" id="input-status-failed" value="failed"
                                class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                {{ is_array(request('filterByStatus')) && in_array('failed', request('filterByStatus')) ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('Failed') }}</span>
                        </label>
                    </div>

                </div>
            </div>

            <button type="submit"
                class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Apply') }}</button>
        </div>
    </form>

    @if ($tasks->isNotEmpty())
        <div class="grid grid-cols-2 gap-4">
            @foreach ($tasks as $task)
                <x-card-index :entityInstance="$task" :entityName="'task'">
                    @if ($task->status === 'pending')
                        <x-heroicon-o-clock class="w-6 h-6 text-yellow-500" />
                    @elseif ($task->status === 'completed')
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-500" />
                    @else
                        <x-heroicon-o-x-circle class="w-6 h-6 text-red-500" />
                    @endif
                </x-card-index>
            @endforeach
        </div>
    @else
        <div class="flex items-center justify-center h-40">
            <p class="text-4xl text-center select-none text-tertiary-bg">{{ __('Waiting tasks...') }}</p>
        </div>
    @endif

    {{ $tasks->links() }}
</x-app-layout>
