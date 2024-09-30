<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="p-6 m-auto rounded-sm shadow-inner form-wrapper h-fit w-fit">
        <h2 class="absolute z-10 hidden text-xl transform -translate-x-1/2 top-3" id="loading"
            style="left: calc(50% + 1.5rem);">
            Loading...</h2>

        <div class="w-20 h-20 m-auto">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-full h-full" />
            </a>
        </div>

        <form action="{{ route('transactions.update', $transaction) }}" method="post">
            @csrf
            @method('PUT')

            <fieldset class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label for="title" class="cursor-pointer text-secondary-txt">{{ __('Title') }}:</label>
                    <input type="text" name="title" placeholder="Title"
                        value="{{ old('title', $transaction->title) }}" id="title"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                    @error('title')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label for="amount" class="cursor-pointer text-secondary-txt">{{ __('Amount') }}:</label>
                    <input type="text" name="amount" placeholder="Amount"
                        value="{{ old('amount', $transaction->amount) }}" id="amount"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                    @error('amount')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label for="type" class="cursor-pointer text-secondary-txt">{{ __('Type') }}:</label>
                    <select id="type" name="type"
                        class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option value="income" {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>
                            Income
                        </option>
                        <option value="expense" {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>
                            Expense
                        </option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="transaction_category_id"
                        class="cursor-pointer text-secondary-txt">{{ __('Category') }}:</label>
                    <select name="transaction_category_id" id="transaction_category_id"
                        class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        @foreach ($transactionCategories as $transactionCategory)
                            <option value="{{ $transactionCategory->id }}"
                                {{ old('transaction_category_id', $transaction->transactionCategory?->id) == $transactionCategory->id ? 'selected' : '' }}>
                                {{ $transactionCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="description" class="cursor-pointer text-secondary-txt">{{ __('Description') }}:</label>
                    <textarea id="description" name="description" rows="4" cols="50"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">{{ old('description', $transaction->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <div class="flex gap-3 mt-8">
                <button type="submit"
                    class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Update') }}</button>
                <a href="{{ route('transactions.index') }}"
                    class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
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
