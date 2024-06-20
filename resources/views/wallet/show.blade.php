<?php
use Illuminate\Support\Str;

$user = auth()->user();
?>

<x-layout>
    <div class="wallet">
        <h3>{{ $full_name }}</h2>

            <p><span>Balance:</span> ${{ $balance }}</p>
            <p><span>Next Bill Due:</span>
                {{ $nextPendingBillDueDate?->format('Y-m-d') ?? 'none' }}
            </p>
            <p><span>Next Task Due:</span>
                {{ $nextPendingTaskDueDate?->format('Y-m-d') ?? 'none' }}
            </p>
            <p><span>Last Transaction:</span>
                @if ($lastTransaction)
                    Amount: ${{ $lastTransaction->amount }} |
                    Type: {{ $lastTransaction->type }} |
                    Category: {{ $lastTransaction->transactionCategory?->name ?? 'none' }} |
                    Description: {{ Str::limit($lastTransaction->description, 5, '...') ?? 'none' }}
                @else
                    None
                @endif
            </p>
    </div>
</x-layout>

