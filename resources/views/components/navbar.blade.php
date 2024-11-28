@php
    $notifications = auth()->user()->notifications;

    $locales = [
        'pt_BR' => ['flag' => '🇧🇷', 'name' => __('pt-BR')],
        'en' => ['flag' => '🇬🇧', 'name' => __('en-US')],
    ];
    $currentLocale = app()->getLocale();
    Log::info(app()->getLocale() . ' navbar');
@endphp

<nav aria-label="secondary" x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-end px-4 py-4 transition-transform duration-500 sm:px-6 bg-dark-eval-1"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="flex items-center p-2 text-sm font-medium rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring focus:ring-[#e4aa70] focus:ring-offset-1 focus:ring-offset-dark-eval-1 text-gray-400 hover:text-gray-200">
                    <div class="ml-1">
                        <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M12,2A10,10,0,1,0,22,12,10.011,10.011,0,0,0,12,2Zm0,18a8,8,0,0,1-8-8,7.963,7.963,0,0,1,1.757-4.9L12,13.586Zm0-11.172L6.343,4.1A7.963,7.963,0,0,1,12,4a7.963,7.963,0,0,1,5.657,2.1ZM12,20a7.963,7.963,0,0,1-5.657-2.1L12,10.414Zm0-11.172L17.657,4.1A7.963,7.963,0,0,1,12,4,7.963,7.963,0,0,1,6.343,4.1Z" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <!-- Language Selection -->
            <x-slot name="content">
                @foreach ($locales as $localeCode => $locale)
                    @php
                        $isActive = $currentLocale === $localeCode;
                        $cssClasses = $isActive
                            ? 'text-tertiary-txt hover:text-secondary-txt'
                            : 'text-gray-400 hover:text-gray-200';
                    @endphp
                    <x-dropdown-link :href="route('locale', ['locale' => $localeCode])" :class="$cssClasses">
                        {{ $locale['flag'] }} {{ $locale['name'] }}
                    </x-dropdown-link>
                @endforeach
            </x-slot>
        </x-dropdown>

        @auth
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="flex items-center p-2 text-sm font-medium rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring focus:ring-[#e4aa70] focus:ring-offset-1 focus:ring-offset-dark-eval-1 text-gray-400 hover:text-gray-200">

                        <div class="ml-1">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Profile -->
                    <x-dropdown-link :href="route('profile.edit')" class="text-gray-400 hover:text-gray-200">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Notifications Preferences -->
                    <x-dropdown-link :href="route('user-available-notifications.index')" class="text-gray-400 hover:text-gray-200">
                        {{ __('Notifications') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-gray-400 hover:text-gray-200">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>

            <x-dropdown align="right" width="64 md:w-80">
                <x-slot name="trigger">
                    <button
                        class="flex items-center p-2 text-sm font-medium rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring focus:ring-[#e4aa70] focus:ring-offset-1 focus:ring-offset-dark-eval-1 text-gray-400 hover:text-gray-200">
                        <div class="ml-1">
                            <x-heroicon-o-bell class="w-6 h-6" />
                        </div>
                    </button>
                </x-slot>

                <!-- Notifications -->
                <x-slot name="content">
                    <div class="w-64 overflow-y-auto md:w-80 max-h-60">
                        @forelse ($notifications as $notification)
                            @php $isUnread = is_null($notification->read_at); @endphp

                            <div class="p-2 border-gray-600 {{ $isUnread ? 'bg-gray-800 hover:bg-gray-800/5' : 'bg-gray-700/70 hover:bg-gray-700/5' }} transition duration-100 ease-in-out">
                                <x-dropdown-link :href="route('notification.read', $notification)">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            @if ($isUnread)
                                                <x-heroicon-s-bell class="w-5 h-5 text-gray-400" />
                                            @else
                                                <x-heroicon-o-bell class="w-5 h-5 text-gray-400" />
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm {{ $isUnread ? 'text-gray-200' : 'text-gray-400' }}">
                                                {{ $notification->data['message'] }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </x-dropdown-link>
                            </div>
                        @empty
                            <div class="p-2 text-sm text-gray-400">
                                No notifications available.
                            </div>
                        @endforelse
                    </div>
                </x-slot>
            </x-dropdown>
        @endauth
    </div>
</nav>

<!-- Mobile bottom bar -->
<div class="fixed inset-x-0 bottom-0 z-10 flex items-center px-4 py-4 transition-transform duration-500 sm:px-6 md:hidden bg-dark-eval-1"
    :class="{
        'translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    @if (!Request::is('/'))
        <div class="flex justify-between w-full">
            <a href="{{ route('dashboard') }}">
                <x-application-logo aria-hidden="true" class="w-10 h-10" />

                <span class="sr-only">Dashboard</span>
            </a>

            <x-button type="button" icon-only variant="secondary" sr-text="Open main menu"
                x-on:click="isSidebarOpen = !isSidebarOpen">
                <x-heroicon-o-menu x-show="!isSidebarOpen" aria-hidden="true" class="w-6 h-6" />

                <x-heroicon-o-x x-show="isSidebarOpen" aria-hidden="true" class="w-6 h-6" />
            </x-button>
        </div>
    @else
        <div class="flex justify-center w-full">
            <a href="{{ route('dashboard') }}">
                <x-application-logo aria-hidden="true" class="w-10 h-10" />

                <span class="sr-only">Dashboard</span>
            </a>
        </div>
    @endif
</div>
