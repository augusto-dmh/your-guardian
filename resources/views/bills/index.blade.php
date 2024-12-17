<x-app-layout>
    <a type="button" href="{{ route('bills.create') }}"
        class="inline-block px-4 py-1 my-4 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Create') }}</a>

    <x-slot name="header">
        <h2 class="text-4xl font-bold text-secondary-txt">{{ __('Bills') }}</h2>
    </x-slot>

    <form method="GET" action="{{ route('bills.index') }}">
        <div class="flex flex-col gap-3 pb-4 m-auto xl:justify-start lg:flex-row xl:gap-12">
            <div class="flex flex-col items-start gap-4 sm:items-end md:flex-row sm:flex-row">
                <x-selects-sort :fields="$sortFields" />

                <x-checkboxes-filter :fields="$filterFields" />
            </div>
            <div class="flex items-end gap-4">
                <button type="submit"
                    class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Apply') }}</button>

                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer" id="table-view-toggle"
                        {{ Auth::user()->index_view_preference === 'table' ? 'checked' : '' }}>
                    <div
                        class="relative w-11 h-6 peer-focus:outline-none peer-focus:ring-4 ring-orange-300 peer-focus:bg-quinary-bg rounded-full peer bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all border-gray-600 peer-checked:bg-quinary-bg">
                    </div>
                    <span class="text-sm font-medium text-gray-300 ms-3">{{ __('Table view') }}</span>
                </label>
            </div>
        </div>

        <x-search-input :searchTerm="$searchTerm" />
    </form>

    @if ($bills->isNotEmpty())
        @if (auth()->user()->index_view_preference === 'cards')
            <x-cards :instances="$bills"/>
        @else
            <x-table :instances="$bills" />
        @endif
    @else
        <div class="flex items-center justify-center h-40">
            <p class="text-4xl text-center select-none text-tertiary-bg">{{ __('Waiting bills...') }}</p>
        </div>
    @endif

    <div class="mt-2">
        {{ $bills->appends(Request::except('page'))->links() }}
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
