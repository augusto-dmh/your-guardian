<x-layout>
    <h2>Bills</h2>

    <form method="GET" action="{{ route('bills.index') }}">
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
                <p>Due Date:</p>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByDueDate" id="sortByDueDateAsc" value="asc"
                        {{ request('sortByDueDate') == 'asc' ? 'checked' : '' }}>
                    <label for="sortByDueDateAsc">Ascending</label>
                </div>
                <div class="flex flex-row flex-nowrap items-center gap-2">
                    <input type="checkbox" name="sortByDueDate" id="sortByDueDateDesc" value="desc"
                        {{ request('sortByDueDate') == 'desc' ? 'checked' : '' }}>
                    <label for="sortByDueDateDesc">Descending</label>
                </div>
            </div>

            <div>
                <h5>Filter by:</h5>
                <div class="form-group">
                    <p>Status:</p>
                    <div class="flex flex-row flex-nowrap items-center gap-2">
                        <input type="checkbox" name="filterByStatus[]" id="filterByStatusPending" value="pending"
                            {{ is_array(request('filterByStatus')) && in_array('pending', request('filterByStatus')) ? 'checked' : '' }}>
                        <label for="filterByStatusPending">Pending</label>
                    </div>
                    <div class="flex flex-row flex-nowrap items-center gap-2">
                        <input type="checkbox" name="filterByStatus[]" id="filterByStatusPaid" value="paid"
                            {{ is_array(request('filterByStatus')) && in_array('paid', request('filterByStatus')) ? 'checked' : '' }}>
                        <label for="filterByStatusPaid">Paid</label>
                    </div>
                    <div class="flex flex-row flex-nowrap items-center gap-2">
                        <input type="checkbox" name="filterByStatus[]" id="filterByStatusOverdue" value="overdue"
                            {{ is_array(request('filterByStatus')) && in_array('overdue', request('filterByStatus')) ? 'checked' : '' }}>
                        <label for="filterByStatusOverdue">Overdue</label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit">Apply</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Bill Amount</th>
                <th>Bill Title</th>
                <th>Bill Description</th>
                <th>Bill Due Date</th>
                <th>Bill Status</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bills as $bill)
                <tr>
                    <td>{{ $bill->amount }}</td>
                    <td>{{ $bill->title }}</td>
                    <td>{{ Str::limit($bill->description, 20, '...') }}</td>
                    <td>{{ $bill->due_date }}</td>
                    <td>{{ $bill->status }}</td>
                    <td>
                        <form action="{{ route('bills.destroy', $bill) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('bills.edit', $bill) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $bills->links() }}
</x-layout>

