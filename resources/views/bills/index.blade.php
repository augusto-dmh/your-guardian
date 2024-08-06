<x-app-layout>
    <a type="button" href="{{ route('bills.create') }}"
        class="inline-block px-4 py-1 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Create') }}</a>

    <x-slot name="header">
        <h2 class="text-4xl font-bold text-secondary-txt">{{ __('Bills') }}</h2>
    </x-slot>

    <form method="GET" action="{{ route('bills.index') }}">
        <div class="flex items-center gap-8 py-6 m-auto">
            <div class="flex items-center gap-4">
                <h5 class="font-semibold text-primary-txt">{{ __('Sort by') }}:</h5>
                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Amount') }}</p>
                    <select name="sortByAmount"
                        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option class="font-thin" value="asc"
                            {{ request('sortByAmount') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
                        </option>
                        <option class="font-thin" value="desc"
                            {{ request('sortByAmount') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Due Date') }}</p>
                    <select name="sortByDueDate"
                        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option class="font-thin" value="asc"
                            {{ request('sortByDueDate') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
                        </option>
                        <option class="font-thin" value="desc"
                            {{ request('sortByDueDate') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="flex gap-8">
                <div class="flex items-center gap-4">
                    <h5 class="font-semibold text-primary-txt">{{ __('Filter by') }}:</h5>
                    <div class="form-group">
                        <p class="mb-1 text-secondary-txt">{{ __('Status') }}</p>
                        <div class="flex flex-col">
                            <label for="input-status-pending"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByStatus[]" id="input-status-pending" value="pending"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ is_array(request('filterByStatus')) && in_array('pending', request('filterByStatus')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Pending') }}</span>
                            </label>
                            <label for="input-status-paid"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByStatus[]" id="input-status-paid" value="paid"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ is_array(request('filterByStatus')) && in_array('paid', request('filterByStatus')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Paid') }}</span>
                            </label>
                            <label for="input-status-overdue"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByStatus[]" id="input-status-overdue" value="overdue"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ is_array(request('filterByStatus')) && in_array('overdue', request('filterByStatus')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Overdue') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">Apply</button>
        </div>
    </form>

    @if ($bills->isNotEmpty())
        <div class="grid grid-cols-2 gap-4">
            @foreach ($bills as $bill)
                <x-card-index :entityInstance="$bill" :entityName="'bill'">
                    @if ($bill->status === 'pending')
                        <x-heroicon-o-clock class="w-6 h-6 text-yellow-500" />
                    @elseif ($bill->status === 'paid')
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-500" />
                    @else
                        <x-heroicon-o-exclamation-circle class="w-6 h-6 text-red-500" />
                    @endif
                </x-card-index>
            @endforeach
        </div>
    @else
        <div class="flex items-center justify-center h-40">
            <p class="text-4xl text-center select-none text-tertiary-bg">{{ __('Waiting bills...') }}</p>
        </div>
    @endif

    {{ $bills->links() }}
</x-app-layout>
