<x-layout>
    <h2>Transactions</h2>

    <div id="overlay" class="hidden fixed inset-0 z-10 cursor-pointer"></div>

    <h2 class="absolute top-0 left-1/2 transform -translate-x-1/2 hidden" id="loading">Loading...</h2>

    <form action="{{ route('transactions.update', $transaction) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" name="amount" placeholder="Amount" value={{ $transaction->amount }}>
            @error('amount')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <label for="type">Type:</label>
            <select id="type" name="type">
                <option value="income" {{ $transaction->type === 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
        </div>
        <div class="form-group">
            <label for="transaction_category_id">Category:</label>
            <select name="transaction_category_id" id="transaction_category_id">
                @foreach ($transactionCategories as $transactionCategory)
                    <option value={{ $transactionCategory->id }}
                        {{ $transactionCategory->name === $transaction->transactionCategory->name ? 'selected' : '' }}>
                        {{ $transactionCategory->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50">{{ $transaction->description }}</textarea><br>
            @error('description')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <button>Update</button>
        </div>
    </form>

    <script defer>
        let isFirstRender = true;

        document.getElementById('type').addEventListener('change', async function() {
            if (isFirstRender) {
                isFirstRender = false;
                return;
            }
            const loadingElement = document.getElementById('loading');
            const overlayElement = document.getElementById('overlay');
            loadingElement.classList.remove('hidden');
            overlayElement.classList.remove('hidden');

            const type = this.value;
            const response = await fetch('/transaction-categories/' + type);
            const categories = await response.json();
            const categorySelect = document.getElementById('transaction_category_id');
            categorySelect.innerHTML = categories.map(function(category) {
                return `<option value="${category.name}">${category.name}</option>`;
            }).join('');

            loadingElement.classList.add('hidden');
            overlayElement.classList.add('hidden');
        });


        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
</x-layout>

