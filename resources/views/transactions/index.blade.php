<x-app-layout>
    <a type="button" href="{{ route('transactions.create') }}"
        class="inline-block px-4 py-1 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Create') }}</a>

    <x-slot name="header">
        <h2 class="text-4xl font-bold text-secondary-txt">{{ __('Transactions') }}</h2>
    </x-slot>

    <form method="GET" action="{{ route('transactions.index') }}">
        <div class="flex items-center gap-8 py-6 m-auto">
            <div class="flex items-center gap-4">
                <h5 class="font-semibold text-primary-txt">{{ __('Sort by:') }}</h5>
                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Amount') }}</p>
                    <select name="sortByAmount"
                        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option class="font-thin" value="asc"
                            {{ request('sortByAmount') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}</option>
                        <option class="font-thin" value="desc"
                            {{ request('sortByAmount') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Date') }}</p>
                    <select name="sortByDate"
                        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option class="font-thin" value="asc"
                            {{ request('sortByDate') == 'asc' ? 'selected' : '' }}>
                            {{ __('Ascending') }}</option>
                        <option class="font-thin" value="desc"
                            {{ request('sortByDate') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-8">
                <div class="flex items-center gap-4">
                    <h5 class="font-semibold text-primary-txt">{{ __('Filter by:') }}</h5>
                    <div class="form-group">
                        <p class="mb-1 text-secondary-txt">{{ __('Type') }}</p>
                        <div class="flex flex-col">
                            <label for="input-type-income"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByType" id="input-type-income" value="income"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ request('filterByType') == 'income' ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Income') }}</span>
                            </label>
                            <label for="input-type-expense"
                                class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByType" id="input-type-expense" value="expense"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ request('filterByType') == 'expense' ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Expense') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Apply') }}</button>
        </div>
    </form>

    @if ($transactions->isNotEmpty())
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
        <div class="flex items-center justify-center h-40">
            <p class="text-4xl text-center select-none text-tertiary-bg">{{ __('Waiting transactions...') }}</p>
        </div>
    @endif

    {{ $transactions->links() }}
</x-app-layout>
