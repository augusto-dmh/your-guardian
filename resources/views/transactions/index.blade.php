<x-layout>
    <h2>Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Amount</th>
                <th>Type</th>
                <th>Category</th>
                <th>Description</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach (auth()->user()->transactions as $transaction)
                <tr>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->type }}</td>
                    <td>{{ $transaction->transactionCategory?->name ?? 'none' }}</td>
                    <td>{{ Str::limit($transaction->description, 20, '...') }}</td>
                    <td>{{ $transaction->created_at->format('m-d-Y') }}</td>
                    <td>
                        <form action="{{ route('transactions.destroy', ['transaction' => $transaction]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('transactions.edit', ['transaction' => $transaction]) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>

