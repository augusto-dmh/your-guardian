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
                        <label
                            class="inline-flex items-center cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                            <input type="checkbox" name="filterByStatus[]" id="filterByStatusPending" value="pending"
                                class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                {{ is_array(request('filterByStatus')) && in_array('pending', request('filterByStatus')) ? 'checked' : '' }}>
                            <span class="ml-2 font-thin">{{ __('Pending') }}</span>
                        </label>
                        <label
                            class="inline-flex items-center cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                            <input type="checkbox" name="filterByStatus[]" id="filterByStatusCompleted"
                                value="completed"
                                class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                {{ is_array(request('filterByStatus')) && in_array('completed', request('filterByStatus')) ? 'checked' : '' }}>
                            <span class="ml-2 font-thin">{{ __('Completed') }}</span>
                        </label>
                        <label
                            class="inline-flex items-center cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                            <input type="checkbox" name="filterByStatus[]" id="filterByStatusFailed" value="failed"
                                class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                {{ is_array(request('filterByStatus')) && in_array('failed', request('filterByStatus')) ? 'checked' : '' }}>
                            <span class="ml-2 font-thin">{{ __('Failed') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Apply') }}</button>
        </div>
    </form>

    <div class="w-full overflow-x-auto rounded-lg">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-secondary-bg">
                <tr>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Category') }}</th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Status') }}</th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Title') }}</th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Description') }}</th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Due Date') }}</th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-tertiary-bg">
                @foreach ($tasks as $task)
                    <tr
                        class="{{ $loop->iteration % 2 == 0 ? 'text-tertiary-txt bg-secondary-bg' : 'text-secondary-txt bg-tertiary-bg' }}">
                        <td class="p-3">{{ $task->taskCategory?->name ?? __('none') }}</td>
                        <td class="p-3">{{ $task->status }}</td>
                        <td class="p-3"><a href="{{ route('tasks.show', $task) }}"
                                class=hover:text-secondary-txt">{{ $task->title }}</a></td>
                        <td class="p-3">{{ Str::limit($task->description, 20, '...') }}</td>
                        <td class="p-3">{{ $task->due_date->format('m-d-Y') }}</td>
                        <td class="flex items-center justify-center p-3 whitespace-nowrap">
                            <form action="{{ route('tasks.destroy', ['task' => $task]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="rounded-full cursor-pointer hover:shadow-inner text-tertiary-txt hover:text-secondary-txt">
                                    <svg class="w-8 h-8 p-1 rounded-full hover:text-secondary-txt" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('tasks.edit', ['task' => $task]) }}"
                                class="rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 21H7v-5L16.732 3.196a2.5 2.5 0 01-1.5-.964z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $tasks->links() }}
</x-app-layout>
