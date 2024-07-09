@php
    $user = auth()->user();
@endphp

<div
    {{ $attributes->merge(['class' => 'flex flex-col gap-2 shadow-custom p-5 rounded-md [&>p]:text-primary-txt [&>p>span]:text-tertiary-txt']) }}>
    <h3 class="inline-block text-xl font-bold text-secondary-txt">{{ $user->full_name }}</h3>

    <p>
        <span>Balance:</span> ${{ $user->balance }}
    </p>
    <p>
        <span>Next Bill Due:</span>
        {{ $user->nextPendingBillDueDate?->format('Y-m-d') ?? 'none' }}
    </p>
    <p>
        <span>Next Task Due:</span>
        {{ $user->nextPendingTaskDueDate?->format('Y-m-d') ?? 'none' }}
    </p>
    <p><span>Last Transaction:</span>
        @if ($user->lastTransaction)
            Amount: ${{ $user->lastTransaction->amount }} |
            Type: {{ $user->lastTransaction->type }} |
            Category: {{ $user->lastTransaction->transactionCategory?->name ?? 'none' }} |
            Description: {{ Str::limit($user->lastTransaction->description, 5, '...') ?? 'none' }}
        @else
            None
        @endif
    </p>
</div>
