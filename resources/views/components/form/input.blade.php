@props([
    'disabled' => false,
    'withicon' => false,
])

@php
    $withiconClasses = $withicon ? 'pl-11 pr-4' : 'px-4';
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        $withiconClasses .
        ' py-2 rounded-md focus:border-gray-400 focus:ring
            focus:ring-purple-500 border-gray-600 bg-dark-eval-1
            text-gray-300 focus:ring-offset-dark-eval-1',
]) !!}>
