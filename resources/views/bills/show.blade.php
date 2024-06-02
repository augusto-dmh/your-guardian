<x-layout>
    <h2>Bill Details</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $bill->title }}</td>
                <td>{{ $bill->description }}</td>
                <td>{{ $bill->amount }}</td>
                <td>{{ $bill->due_date->format('m-d-Y') }}</td>
                <td>{{ $bill->status }}</td>
                <td>{{ $bill->created_at->format('m-d-Y') }}</td>
            </tr>
        </tbody>
    </table>
</x-layout>

