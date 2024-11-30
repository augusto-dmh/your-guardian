@if($modelName === 'bill')
    @if ($instance->status === 'pending')
        <x-heroicon-o-clock class="w-6 h-6 text-yellow-500" />
    @elseif ($instance->status === 'paid')
        <x-heroicon-o-check-circle class="w-6 h-6 text-green-500" />
    @else
        <x-heroicon-o-exclamation-circle class="w-6 h-6 text-red-500" />
    @endif
@endif

@if($modelName === 'task')
    @if ($instance->status === 'pending')
        <x-heroicon-o-clock class="text-yellow-500 " />
    @elseif ($instance->status === 'completed')
        <x-heroicon-o-check-circle class="text-green-500" />
    @else
        <x-heroicon-o-x-circle class="text-red-500" />
    @endif
@endif

@if($modelName === 'transaction')
    @if ($instance->type === 'income')
        <x-heroicon-o-trending-up class="text-green-500 " />
    @else
        <x-heroicon-o-trending-down class="text-red-500" />
    @endif
@endif
