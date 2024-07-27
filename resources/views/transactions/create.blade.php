<x-layout>
    <div id="overlay" class="fixed inset-0 z-10 hidden cursor-pointer"></div>

    <h2 class="absolute top-0 hidden transform -translate-x-1/2 left-1/2" id="loading">{{ __('Loading...') }}</h2>

    <div class="form-wrapper">
        <div class="flex justify-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-32 h-32" />
            </a>
        </div>

        <form action="{{ route('transactions.store') }}" method="post" class="form-main">
            @csrf

            <fieldset>
                <div class="form-group">
                    <label for="amount">{{ __('Amount:') }}</label>
                    <input type="text" name="amount" value="{{ old('amount') }}">
                    @error('amount')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">{{ __('Type:') }}</label>
                    <select id="type" name="type">
                        <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>{{ __('Income') }}
                        </option>
                        <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>{{ __('Expense') }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="transaction_category_id">{{ __('Category:') }}:</label>
                    <select name="transaction_category_id" id="transaction_category_id">
                        <option value="">{{ __('Select a category') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('Description:') }}</label>
                    <textarea id="description" name="description" rows="4" cols="50">{{ old('description') }}</textarea>
                    @error('description')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <div class="form-group">
                <button>{{ __('Create transaction') }}</button>
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
                const oldCategoryId = "{{ old('transaction_category_id') }}";

                categories.forEach((category) => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;

                    if (oldCategoryId == category.id) {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                });

                loadingElement.classList.add('hidden');
                overlayElement.style.display = 'none';
            });

            document.getElementById('type').dispatchEvent(new Event('change'));
        </script>
    </div>
</x-layout>
