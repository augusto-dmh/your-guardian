@props([
    'variant' => 'primary',
    'iconOnly' => false,
    'srText' => '',
    'href' => false,
    'size' => 'base',
    'disabled' => false,
    'pill' => false,
    'squared' => false,
])

@php

    $baseClasses =
        'inline-flex items-center transition-colors font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white focus:ring-offset-dark-eval-2';

    switch ($variant) {
        case 'primary':
            $variantClasses = 'bg-[#e4aa70] text-[#ffedd5] hover:bg-[#e4aa70] focus:ring-[#e4aa70]';
            break;
        case 'secondary':
            $variantClasses =
                'focus:ring-[#e4aa70] text-[#e4aa70] bg-dark-eval-1 hover:bg-dark-eval-2 hover:text-[#fac189]';
            break;
        case 'success':
            $variantClasses = 'bg-green-400 text-[#ffedd5] hover:bg-green-600 focus:ring-green-400';
            break;
        case 'danger':
            $variantClasses = 'bg-red-400 text-[#ffedd5] hover:bg-red-600 focus:ring-red-400';
            break;
        case 'warning':
            $variantClasses = 'bg-yellow-400 text-[#ffedd5] hover:bg-yellow-600 focus:ring-yellow-400';
            break;
        case 'info':
            $variantClasses = 'bg-cyan-400 text-[#ffedd5] hover:bg-cyan-600 focus:ring-cyan-400';
            break;
        case 'black':
            $variantClasses = 'bg-black text-gray-300 hover:text-[#ffedd5] focus:ring-black hover:bg-dark-eval-3';
            break;
        default:
            $variantClasses = 'bg-[#e4aa70] text-[#ffedd5] hover:bg-[#e4aa70] focus:ring-[#e4aa70]';
    }

    switch ($size) {
        case 'sm':
            $sizeClasses = $iconOnly ? 'p-1.5' : 'px-2.5 py-1.5 text-sm';
            break;
        case 'base':
            $sizeClasses = $iconOnly ? 'p-2' : 'px-4 py-2 text-base';
            break;
        case 'lg':
        default:
            $sizeClasses = $iconOnly ? 'p-3' : 'px-5 py-2 text-xl';
            break;
    }

    $classes = $baseClasses . ' ' . $sizeClasses . ' ' . $variantClasses;

    if (!$squared && !$pill) {
        $classes .= ' rounded-md';
    } elseif ($pill) {
        $classes .= ' rounded-full';
    }

@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
        @if ($iconOnly)
            <span class="sr-only">{{ $srText ?? '' }}</span>
        @endif
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
        @if ($iconOnly)
            <span class="sr-only">{{ $srText ?? '' }}</span>
        @endif
    </button>
@endif
