<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    <x-sidebar.link title="{{ __('Dashboard') }}" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="{{ __('Charts') }}" :active="Str::startsWith(request()->route()->uri(), 'charts')" isParentDropdown>
        <x-slot name="icon">
            <x-heroicon-o-chart-bar class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.dropdown title="{{ __('Transactions') }}" :active="Str::startsWith(request()->route()->uri(), 'transactions')">
            <x-slot name="icon">
                <x-heroicon-s-switch-horizontal class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.button class="transactions-total-paid-btn" title="{{ __('Total paid') }}" :active="request()->routeIs('transactions.total_paid')" />
        </x-sidebar.dropdown>

        <x-sidebar.dropdown title="{{ __('Bills') }}" :active="Str::startsWith(request()->route()->uri(), 'bills')">
            <x-slot name="icon">
                <x-heroicon-o-document-text class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.button class="bills-n-of-paid-btn" title="{{ __('Nº of paid') }}" :active="request()->routeIs('bills.n_of_paid')" />
        </x-sidebar.dropdown>

    </x-sidebar.dropdown>

    <script></script>

    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        {{ __('Your things') }}
    </div>

    <x-sidebar.link title="{{ __('Bills') }}" href="{{ route('bills.index') }}">
        <x-slot:icon>
            <x-heroicon-o-document-text class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot:icon>
    </x-sidebar.link>
    <x-sidebar.link title="{{ __('Transactions') }}" href="{{ route('transactions.index') }}">
        <x-slot:icon>
            <x-heroicon-o-switch-horizontal class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot:icon>
    </x-sidebar.link>
    <x-sidebar.link title="{{ __('Tasks') }}" href="{{ route('tasks.index') }}">
        <x-slot:icon>
            <x-heroicon-o-clipboard-list class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot:icon>
    </x-sidebar.link>

</x-perfect-scrollbar>
