<x-layout>
    <div id="overlay" class="hidden fixed inset-0 z-10 cursor-pointer"></div>

    <h2 class="absolute top-0 left-1/2 transform -translate-x-1/2 hidden" id="loading">Loading...</h2>

    <div class="form-wrapper">
        <x-your-guardian-logo-1 />

        <form action="{{ route('transactions.update', $transaction) }}" method="post" class="form-main">
            @csrf
            @method('PUT')

            <fieldset>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" name="amount" placeholder="Amount"
                        value="{{ old('amount', $transaction->amount) }}">
                    @error('amount')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select id="type" name="type">
                        <option value="income" {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>
                            Income
                        </option>
                        <option value="expense" {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>
                            Expense
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="transaction_category_id">Category:</label>
                    <select name="transaction_category_id" id="transaction_category_id">
                        @foreach ($transactionCategories as $transactionCategory)
                            <option value="{{ $transactionCategory->id }}"
                                {{ old('transaction_category_id', $transaction->transactionCategory->id) == $transactionCategory->id ? 'selected' : '' }}>
                                {{ $transactionCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50">{{ old('description', $transaction->description) }}</textarea>
                    @error('description')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

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
                const oldCategoryId = "{{ old('transaction_category_id') }}";

                categorySelect.innerHTML = categories.map(function(category) {
                    return `<option value="${category.id}" ${oldCategoryId == category.id ? 'selected' : ''}>${category.name}</option>`;
                }).join('');

                loadingElement.classList.add('hidden');
                overlayElement.classList.add('hidden');
            });

            document.getElementById('type').dispatchEvent(new Event('change'));
        </script>
    </div>
</x-layout>

