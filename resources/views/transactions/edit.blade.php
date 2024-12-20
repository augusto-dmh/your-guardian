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

        @foreach($textFields as $textField)
            <x-edit-form-text-field :field="$textField" />
        @endforeach

        @foreach($selectFields as $selectField)
            <x-edit-form-select-field :field="$selectField" :options="$selectField['options']" />
        @endforeach
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
