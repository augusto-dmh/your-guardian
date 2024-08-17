<x-app-layout>
    <a type="button" href="{{ route('tasks.create') }}"
        class="inline-block px-4 py-1 mb-4 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Create') }}</a>

    <x-slot name="header">
        <h2 class="text-4xl font-bold text-secondary-txt">{{ __('Tasks') }}</h2>
    </x-slot>

    <form method="GET" action="{{ route('tasks.index') }}">
        <div class="flex flex-col gap-2 m-auto lg:gap-12 lg:flex-row">
            <div class="flex items-center gap-6 my-4 md:flex-row">
                <div class="flex items-center gap-4">
                    <h5 class="font-semibold text-primary-txt">{{ __('Sort by') }}:</h5>
                    <div class="flex gap-2">
                        <div class="form-group">
                            <p class="mb-1 text-secondary-txt">{{ __('Due date') }}</p>
                            <select name="sortByDueDate"
                                class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                                <option class="font-thin" value="">
                                    {{ __('Select') }}
                                </option>
                                <option class="font-thin" value="asc"
                                    {{ request('sortByDueDate') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
                                </option>
                                <option class="font-thin" value="desc"
                                    {{ request('sortByDueDate') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <h5 class="font-semibold text-primary-txt">{{ __('Filter by') }}:</h5>
                    <div class="form-group">
                        <p class="mb-1 text-secondary-txt">{{ __('Status') }}</p>
                        <div class="flex flex-col">
                            <label for="input-status-pending"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByStatus[]" id="input-status-pending" value="pending"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ request('filterByStatus') && in_array('pending', request('filterByStatus')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Pending') }}</span>
                            </label>
                            <label for="input-status-completed"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByStatus[]" id="input-status-completed"
                                    value="completed"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ request('filterByStatus') && in_array('completed', request('filterByStatus')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Completed') }}</span>
                            </label>
                            <label for="input-status-failed"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByStatus[]" id="input-status-failed" value="failed"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ request('filterByStatus') && in_array('failed', request('filterByStatus')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Failed') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">Apply</button>

                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer" id="table-view-toggle"
                        {{ Auth::user()->index_view_preference === 'table' ? 'checked' : '' }}>
                    <div
                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 ring-orange-300 peer-focus:bg-quinary-bg dark:peer-focus:bg-quinary-bg rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-quinary-bg">
                    </div>
                    <span
                        class="text-sm font-medium text-gray-900 ms-3 dark:text-gray-300">{{ __('Table view') }}</span>
                </label>
            </div>
        </div>
    </form>

    <div>
        @if ($tasks->isNotEmpty())
            @if (auth()->user()->index_view_preference === 'cards')
                <div class="grid grid-cols-2 gap-4 my-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
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
                <div class="w-full my-4 overflow-x-auto rounded-lg">
                    <table class="w-full bg-secondary-bg">
                        <x-table-index-columns :entity="\App\Models\Task::class" />
                        @foreach ($tasks as $task)
                            <tr
                                class="{{ $loop->iteration % 2 == 0 ? 'text-tertiary-txt bg-secondary-bg' : 'text-secondary-txt bg-tertiary-bg' }}">
                                <x-table-index-row :entityName="'task'" :entityInstance="$task" />
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        @else
            <div class="flex items-center justify-center h-40">
                <p class="text-4xl text-center select-none text-tertiary-bg">{{ __('Waiting tasks...') }}</p>
            </div>
        @endif
    </div>

    {{ $tasks->appends(Request::except('page'))->links() }}
</x-app-layout>

<script>
    const tableView = document.querySelector('#table-view-toggle');
    tableView.addEventListener('change', function() {
        const isChecked = tableView.checked;
        const viewPreference = isChecked ? 'table' : 'cards';
        fetch(`/index-view-preference-switch/${viewPreference}`, {
            method: 'GET',
        }).then(response =>
            location.reload());
    });
</script>
