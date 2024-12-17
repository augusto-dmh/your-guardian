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
                <button type="submit" class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Apply') }}</button>
                <x-switch-view-button />
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
        <x-waiting-instances-message :modelInstances="$bills" />
    @endif

    <div class="mt-2">
        {{ $bills->appends(Request::except('page'))->links() }}
    </div>
</x-app-layout>
