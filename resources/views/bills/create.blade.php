<x-layout>
    <div class="form-wrapper">
        <x-your-guardian-logo-1 />

        <form action="{{ route('bills.store') }}" method="post" class="form-main">
            @csrf

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" name="amount" placeholder="Amount">
            </div>
            <div class="form-group">
                <label for="due_date">Due date:</label>
                <input type="date" name="due_date" id="due_date">
            </div>

            <div class="form-group">
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
            </div>

            <div class="form-group">
                <button>Create Bill</button>
            </div>
        </form>
    </div>
</x-layout>

