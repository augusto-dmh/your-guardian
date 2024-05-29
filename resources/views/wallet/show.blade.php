<?php
use Illuminate\Support\Str;

$user = auth()->user();
?>

<div>
    <h3>{{ $user->full_name }}</h2>

        <p>Balance: ${{ $user->balance }}</p>
        <p>Next Bill Due: {{ $user->bills()->orderBy('due_date', 'asc')->first() ?? 'none' }}</p>
        <p>Next Task Due: {{ $user->tasks()->orderBy('due_date', 'asc')->first() ?? 'none' }}</p>
        <p>Last Transaction:
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

