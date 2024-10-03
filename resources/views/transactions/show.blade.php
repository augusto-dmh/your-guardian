<x-show-view :model="$transaction">
    <x-slot:form-fields>
        <x-show-form-field
            :label="__('Amount:')"
            :value="$transaction->amount"
        />

        <x-show-form-field
            :label="__('Type:')"
            :value="__($transaction->type)"
        />

        <x-show-form-field
            :label="__('Category:')"
            :value="__($transaction->transactionCategory?->name ?? 'N/A')"
        />

        <x-show-form-field
            :label="__('Made in:')"
            :value="formatDate($transaction->created_at)"
        />
    </x-slot:form-fields>
</x-show-view>
