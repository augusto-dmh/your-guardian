<x-layout>
    <div id="overlay" class="hidden fixed inset-0 z-10 cursor-pointer"></div>

    <h2 class="absolute top-0 left-1/2 transform -translate-x-1/2 hidden" id="loading">Loading...</h2>

    <form action="{{ route('transactions.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" name="amount" placeholder="Amount" value="">
            @error('amount')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group">
            <label for="type">Type:</label>
            <select id="type" name="type">
                <option value="income">Income</option>
                <option value="expense" selected>Expense</option>
            </select>
        </div>
        <div class="form-group">
            <label for="transaction_category_id">Category:</label>
            <select name="transaction_category_id" id="transaction_category_id"></select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
            @error('description')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <button>Create transaction</button>
        </div>
    </form>

    <script>
        document.getElementById('type').addEventListener('change', async function() {
            const loadingElement = document.getElementById('loading');
            const overlayElement = document.getElementById('overlay');
            loadingElement.classList.remove('hidden');
            overlayElement.style.display = 'block';

            const type = this.value;
            const response = await fetch('/transaction-categories/' + type);
            const categories = await response.json();
            const categorySelect = document.getElementById('transaction_category_id');
            console.log(categorySelect);
            categorySelect.innerHTML = categories.map(function(category) {
                return `<option value="${category.id}">${category.name}</option>`;
            }).join('');

            loadingElement.classList.add('hidden');
            overlayElement.style.display = 'none';
        });

        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
</x-layout>

