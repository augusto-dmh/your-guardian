<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="Charts" :active="Str::startsWith(request()->route()->uri(), 'buttons')" isParentDropdown>
        <x-slot name="icon">
            <x-heroicon-o-chart-bar class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.dropdown title="Transactions" :active="Str::startsWith(request()->route()->uri(), 'buttons')">
            <x-slot name="icon">
                <x-heroicon-s-switch-horizontal class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.button class="transactions-total-paid-btn" title="Total paid" :active="request()->routeIs('buttons.text')" />
        </x-sidebar.dropdown>

        <x-sidebar.dropdown title="Bills" :active="Str::startsWith(request()->route()->uri(), 'buttons')">
            <x-slot name="icon">
                <x-heroicon-o-document-text class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.button class="bills-n-of-paid-btn" title="Nº of paid" :active="request()->routeIs('buttons.text')" />
        </x-sidebar.dropdown>

    </x-sidebar.dropdown>

    <script></script>

    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        Your things
    </div>

    <x-sidebar.link title="Bills" href="{{ route('bills.index') }}">
        <x-slot:icon>
            <x-heroicon-o-document-text class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot:icon>
    </x-sidebar.link>
    <x-sidebar.link title="Transactions" href="{{ route('transactions.index') }}">
        <x-slot:icon>
            <x-heroicon-o-switch-horizontal class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot:icon>
    </x-sidebar.link>
    <x-sidebar.link title="Tasks" href="{{ route('tasks.index') }}">
        <x-slot:icon>
            <x-heroicon-o-clipboard-list class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot:icon>
    </x-sidebar.link>

</x-perfect-scrollbar>
