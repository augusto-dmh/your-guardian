<x-app-layout>
    <a type="button" href="{{ route('transactions.create') }}"
        class="inline-block px-4 py-1 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Create') }}</a>

    <x-slot name="header">
        <h2 class="text-4xl font-bold text-secondary-txt">{{ __('Transactions') }}</h2>
    </x-slot>

    <form method="GET" action="{{ route('transactions.index') }}">
        <div class="flex flex-col gap-3 pb-8 m-auto mt-6 lg:justify-between xl:justify-start lg:flex-row xl:gap-12">
            <div class="flex items-end gap-4 md:flex-row">
                <div class="flex items-end gap-4">
                    <div class="flex items-end gap-2">
                        <div class="form-group">
                            <p class="mb-1 text-secondary-txt">{{ __('Amount') }}</p>
                            <select name="sortByAmount"
                                class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                                <option class="font-thin" value="">
                                    {{ __('Sort by') }}
                                </option>
                                <option class="font-thin" value="asc"
                                    {{ request('sortByAmount') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
                                </option>
                                <option class="font-thin" value="desc"
                                    {{ request('sortByAmount') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <p class="mb-1 text-secondary-txt">{{ __('Date') }}</p>
                            <select name="sortByDate"
                                class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                                <option class="font-thin" value="">
                                    {{ __('Sort by') }}
                                </option>
                                <option class="font-thin" value="asc"
                                    {{ request('sortByDate') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
                                </option>
                                <option class="font-thin" value="desc"
                                    {{ request('sortByDate') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-end gap-4">
                    <div class="form-group">
                        <p class="mb-1 text-secondary-txt">{{ __('Type') }}</p>
                        <div class="flex flex-col">
                            <label for="input-type-income"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByType[]" id="input-type-income" value="income"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ request('filterByType') && in_array('income', request('filterByType')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Income') }}</span>
                            </label>
                            <label for="input-type-expense"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByType[]" id="input-type-expense" value="expense"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ request('filterByType') && in_array('expense', request('filterByType')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Expense') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-end gap-4">
                <button type="submit"
                    class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Apply') }}</button>

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

    @if ($transactions->isNotEmpty())
        @if (auth()->user()->index_view_preference === 'cards')
            <div class="grid grid-cols-2 gap-4">
                @foreach ($transactions as $transaction)
                    <x-card-index :entityInstance="$transaction" :entityName="'transaction'">
                        @if ($transaction->type === 'income')
                            <x-heroicon-o-trending-up class="w-6 h-6 text-green-500" />
                        @else
                            <x-heroicon-o-trending-down class="w-6 h-6 text-red-500" />
                        @endif
                    </x-card-index>
                @endforeach
            </div>
        @else
            <div class="w-full overflow-x-auto rounded-lg">
                <table class="w-full bg-secondary-bg">
                    <x-table-index-columns :entity="\App\Models\Transaction::class" />
                    @foreach ($transactions as $transaction)
                        <tr
                            class="{{ $loop->iteration % 2 == 0 ? 'text-tertiary-txt bg-secondary-bg' : 'text-secondary-txt bg-tertiary-bg' }}">
                            <x-table-index-row :entityName="'transaction'" :entityInstance="$transaction" />
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    @else
        <div class="flex items-center justify-center h-40">
            <p class="text-4xl text-center select-none text-tertiary-bg">{{ __('Waiting transactions...') }}</p>
        </div>
    @endif

    <div class="mt-2">
        {{ $transactions->appends(Request::except('page'))->links() }}
    </div>
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
