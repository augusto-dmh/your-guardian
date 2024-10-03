<x-show-view :model="$bill">
    <x-slot:exclusive-action-button>
        @if ($bill->status !== 'paid')
            <x-pay-bill-button :bill="$bill" />
        @endif
    </x-slot:exclusive-action-button>

    <x-slot:form-fields>
        <x-show-form-field
            :label="__('Amount:')"
            :value="$bill->amount"
        />
        <x-show-form-field
            :label="__('Category:')"
            :value="__($bill->billCategory?->name ?? 'N/A')"
        />
        <x-show-form-field
            :label="__('Status:')"
            :value="__($bill->status)"
        />
        <x-show-form-field
            :label="__('Created at:')"
            :value="formatDate($bill->created_at)"
        />
        <x-show-form-field
            :label="__('Due date:')"
            :value="formatDate($bill->due_date)"
        />

        @if ($bill->paid_at)
            <x-show-form-field
                :label="__('Paid at:')"
                :value="formatDate($bill->paid_at)"
            />
        @endif
    </x-slot:form-fields>
</x-show-view>
