<x-layout>
    <h2>Bills</h2>
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
</x-layout>

