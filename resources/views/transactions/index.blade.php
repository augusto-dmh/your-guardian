<x-layout>
    <h2>Transactions</h2>

    <form method="GET" action="{{ route('transactions.index') }}">
        <div>
            <h5>Sort by:</h5>
            <div class="form-group">
                <p>Amount:</p>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByAmount" id="sortByAmountAsc" value="asc"
                        {{ request('sortByAmount') == 'asc' ? 'checked' : '' }}>
                    <label for="sortByAmountAsc">Ascending</label>
                </div>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByAmount" id="sortByAmountDesc" value="desc"
                        {{ request('sortByAmount') == 'desc' ? 'checked' : '' }}>
                    <label for="sortByAmountDesc">Descending</label>
                </div>
            </div>

            <div class="form-group">
                <p>Date:</p>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByDate" id="sortByDateAsc" value="asc"
                        {{ request('sortByDate') == 'asc' ? 'checked' : '' }}>
                    <label for="sortByDateAsc">Ascending</label>
                </div>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByDate" id="sortByDateDesc" value="desc"
                        {{ request('sortByDate') == 'desc' ? 'checked' : '' }}>
                    <label for="sortByDateDesc">Descending</label>
                </div>
            </div>
        </div>

        <div>
            <h5>Filter by:</h5>
            <div class="form-group">
                <p>Type:</p>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="filterByType" id="filterByTypeIncome" value="income"
                        {{ request('filterByType') == 'income' ? 'checked' : '' }}>
                    <label for="filterByTypeIncome">Income</label>
                </div>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="filterByType" id="filterByTypeIncome" value="expense"
                        {{ request('filterByType') == 'expense' ? 'checked' : '' }}>
                    <label for="filterByTypeIncome">Expense</label>
                </div>
            </div>
        </div>

        <button type="submit">Apply</button>
    </form>

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
            @foreach ($transactions as $transaction)
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

    {{ $transactions->links() }}
</x-layout>

