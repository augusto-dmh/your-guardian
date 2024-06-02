<x-layout>
    <div class="form-wrapper">
        <x-your-guardian-logo-1 />

        <form action="{{ route('bills.update', $bill) }}" method="post" class="form-main">
            @csrf
            @method('PUT')

            <fieldset>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" placeholder="Title" value="{{ $bill->title }}">
                </div>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" name="amount" placeholder="Amount" value="{{ $bill->amount }}">
                </div>
                <div class="form-group">
                    <label for="due_date">Due date:</label>
                    <input type="date" name="due_date" id="due_date" value="{{ $bill->due_date }}">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50">{{ $bill->description }}</textarea>
                </div>
                <div class="form-group">
                    <select name="status" id="status">
                        <option value="pending" {{ $bill->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $bill->status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ $bill->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
            </fieldset>

            <div class="form-group">
                <button>Update Bill</button>
            </div>
        </form>
    </div>

</x-layout>
