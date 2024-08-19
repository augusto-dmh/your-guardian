@php
    $user = auth()->user();
@endphp

<div
    {{ $attributes->merge(['class' => 'flex flex-col gap-2 bg-secondary-bg p-5 rounded-md [&>p]:text-primary-txt [&>p>span]:text-tertiary-txt']) }}>
    <h3 class="inline-block text-xl font-bold text-secondary-txt">{{ $user->full_name }}</h3>

    <p>
        <span>{{ __('Balance:') }}</span> ${{ $user->balance }}
    </p>
    <p>
        <span>{{ __('Next Bill Due:') }}</span>
        {{ $user->nextPendingBillDueDate?->format('Y-m-d') ?? __('not available') }}
    </p>
    <p>
        <span>{{ __('Next Task Due:') }}</span>
        {{ $user->nextPendingTaskDueDate?->format('Y-m-d') ?? __('not available') }}
    </p>
    <p><span>{{ __('Last Transaction:') }}</span>
        @if ($user->lastTransaction)
            <a href="{{ route('transactions.show', $user->lastTransaction) }}" class="hover:underline">
                {{ __('Amount:') }} ${{ $user->lastTransaction->amount }} |
                {{ __('Title:') }} ${{ Str::limit($user->lastTransaction->title, 20, '...') }} |
                {{ __('Category:') }}
                {{ Str::limit($user->lastTransaction->transactionCategory?->name, 10, '...') ?? __('not available') }}
                |
            </a>
        @else
            {{ __('not available') }}
        @endif
    </p>
</div>
