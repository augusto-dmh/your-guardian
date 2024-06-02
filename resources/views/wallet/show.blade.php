<?php
use Illuminate\Support\Str;

$user = auth()->user();
?>

<x-layout>
    <div class="wallet">
        <h3>{{ $user->full_name }}</h2>

            <p><span>Balance:</span> ${{ $user->balance }}</p>
            <p><span>Next Bill Due:</span>
                {{ $user->bills()->orderBy('due_date', 'asc')->first()->due_date->format('Y-m-d') ?? 'none' }}
            </p>
            <p><span>Next Task Due:</span>
                {{ $user->tasks()->orderBy('due_date', 'asc')->first()->due_date->format('Y-m-d') ?? 'none' }}
            </p>
            <p><span>Last Transaction:</span>
                @if ($lastTransaction = $user->transactions->last())
                    Amount: ${{ $lastTransaction->amount }} |
                    Type: {{ $lastTransaction->type }} |
                    Category: {{ $lastTransaction->transactionCategory->name ?? 'none' }} |
                    Description: {{ Str::limit($lastTransaction->description, 5, '...') ?? 'none' }}
                @else
                    None
                @endif
            </p>
    </div>
</x-layout>

