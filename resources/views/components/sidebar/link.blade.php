@props([
    'isActive' => false,
    'title' => '',
    'collapsible' => false,
    'active' => false,
])

@php
    $isActiveClasses = $isActive
        ? 'text-white bg-[#e4aa70] shadow-lg hover:bg-[#fac189]'
        : 'text-[#e4aa70] hover:text-[#fac189] hover:bg-dark-eval-2';

    $classes =
        'flex-shrink-0 flex items-center gap-2 p-2 transition-colors rounded-md overflow-hidden ' . $isActiveClasses;

    if ($collapsible) {
        $classes .= ' w-full';
    }
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon ?? false)
            {{ $icon }}
        @else
            <x-icons.empty-circle class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        @endif

        <span class="text-base font-medium whitespace-nowrap" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>

        <span x-show="isSidebarOpen || isSidebarHovered" aria-hidden="true" class="relative block w-6 h-6 ml-auto">
            <span :class="open ? '-rotate-45' : 'rotate-45'"
                class="absolute right-[9px] bg-[#e4aa70] mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>

            <span :class="open ? 'rotate-45' : '-rotate-45'"
                class="absolute left-[9px] bg-[#e4aa70] mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
        </span>
    </button>
@else
    <a {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon ?? false)
            {{ $icon }}
        @else
            <x-icons.empty-circle class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        @endif

        <span class="text-base font-medium" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>
    </a>
@endif
