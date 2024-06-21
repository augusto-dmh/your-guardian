<x-layout>
    <div class="form-wrapper">
        <div class="flex justify-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-32 h-32" />
            </a>
        </div>

        <form action="{{ route('bills.store') }}" method="post" class="form-main">
            @csrf

            <fieldset>
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
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50"></textarea>
                </div>
            </fieldset>

            <div class="form-group">
                <button>Create Bill</button>
            </div>
        </form>
    </div>
</x-layout>

