<x-app-layout>
    <x-slot name="header"></x-slot>
    <h2 class="absolute z-10 hidden text-xl transform -translate-x-1/2 top-3"
        id="loading"
        style="left: calc(50% + 1.5rem);">
        Loading...
    </h2>

    <x-edit-form :formAction="route('transactions.update', $transaction)"
        :model="$transaction">
        <!-- Transaction Specific Fields -->

        <!-- Amount Field -->
        <div class="flex flex-col gap-1">
            <label for="amount"
                class="cursor-pointer text-secondary-txt">{{ __('Amount') }}:</label>
            <input type="text"
                name="amount"
                placeholder="Amount"
                value="{{ old('amount', $transaction->amount) }}"
                id="amount"
                class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
            @error('amount')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Type Field -->
        <div class="flex flex-col gap-1">
            <label for="type"
                class="cursor-pointer text-secondary-txt">{{ __('Type') }}:</label>
            <select id="type"
                name="type"
                class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                @foreach($transactionTypes as $transactionType)
                    <option value="income"
                        {{ old('type', $transaction->type) === $transactionType ? 'selected' : '' }}>
                        {{ $transactionType }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Category Field -->
        <div class="flex flex-col gap-1">
            <label for="transaction_category_id"
                class="cursor-pointer text-secondary-txt">{{ __('Category') }}:</label>
            <select name="transaction_category_id"
                id="transaction_category_id"
                class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                @foreach ($transactionCategories as $transactionCategory)
                    <option value="{{ $transactionCategory->id }}"
                        {{ old('transaction_category_id', $transaction->transactionCategory?->id) == $transactionCategory->id ? 'selected' : '' }}>
                        {{ $transactionCategory->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </x-edit-form>
</x-app-layout>

<script defer>
    let isFirstRender = true;

    document.getElementById('type').addEventListener('change', async function() {
        if (isFirstRender) {
            isFirstRender = false;
            return;
        }
        const loadingElement = document.getElementById('loading');
        loadingElement.classList.remove('hidden');

        const type = this.value;
        const response = await fetch('/transaction-categories/' + type);
        const categories = await response.json();
        const categorySelect = document.getElementById('transaction_category_id');
        const oldCategoryId = "{{ old('transaction_category_id') }}";

        categorySelect.innerHTML = categories.map(function(category) {
            return `<option value="${category.id}" ${oldCategoryId == category.id ? 'selected' : ''}>${category.name}</option>`;
        }).join('');

        loadingElement.classList.add('hidden');
    });

    document.getElementById('type').dispatchEvent(new Event('change'));
</script>
