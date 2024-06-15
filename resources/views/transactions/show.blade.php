<h2>Transactions</h2>
<table>
    <thead>
        <tr>
            <th>Amount</th>
            <th>Type</th>
            <th>Category</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $transaction->amount }}</td>
            <td>{{ $transaction->type }}</td>
            <td>{{ $transaction->transactionCategory()->name ?? 'none' }}</td>
            <td>{{ $transaction->created_at->format('m-d-Y') }}</td>
        </tr>
    </tbody>
</table>

