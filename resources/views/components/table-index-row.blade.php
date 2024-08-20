@props(['entityName', 'entityInstance'])

@php
    use Illuminate\Support\Str;
    use App\Models\Bill;

    $attributeMapping = [
        'bill_id' => $entityInstance->bill ? 'has_bill' : 'N/A',
        'title' => Str::limit($entityInstance->title, 20, '...'),
        'description' => Str::limit($entityInstance->description ?? 'N/A', 30, '...'),
        'transaction_category_id' => __($entityInstance->transactionCategory?->name ?? 'N/A'),
        'task_category_id' => __($entityInstance->taskCategory?->name ?? 'N/A'),
        'status' => __($entityInstance->status ?? 'N/A'),
        'type' => __($entityInstance->type ?? 'N/A'),
        'due_date' => formatDate($entityInstance->due_date),
        'paid_at' => formatDate($entityInstance->paid_at),
        'created_at' => formatDate($entityInstance->created_at),
    ];

    $transformAttribute = function ($attribute, $entityInstance, $mapping) {
        if (array_key_exists($attribute, $mapping)) {
            return $mapping[$attribute] ?? 'N/A';
        }

        return $entityInstance->$attribute ?? 'N/A';
    };

    $attributes = $entityInstance->getFillable();

    $filteredAttributes = array_filter($attributes, function ($attribute) {
        return $attribute !== 'user_id';
    });
@endphp

@foreach ($filteredAttributes as $attribute)
    <td class="p-3 text-left whitespace-nowrap">
        {{-- @if ($attribute === 'bill_id' && $attributeMapping[$attribute] === 'has_bill')
            <a href="{{ route('bills.show', $entityInstance) }}">
                <x-heroicon-o-document-text class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </a>
        @else --}} {{-- not yet necessary: there's no way already in application-level to associate a bill's status change to 'paid' to the creation of a transaction of status 'paid' --}}
        {{ $transformAttribute($attribute, $entityInstance, $attributeMapping) }}
        {{-- @endif --}}
    </td>
@endforeach
<td class="flex items-center justify-center p-3 font-normal">
    <form action="{{ route($entityName . 's.destroy', $entityInstance) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="block rounded-full cursor-pointer hover:shadow-inner text-tertiary-txt hover:text-secondary-txt">
            <svg class="w-8 h-8 p-1 rounded-full hover:text-secondary-txt" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                </path>
            </svg>
        </button>
    </form>
    <a href="{{ route($entityName . 's.edit', $entityInstance) }}"
        class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 21H7v-5L16.732 3.196a2.5 2.5 0 01-1.5-.964z" />
        </svg>
    </a>
    <a href="{{ route($entityName . 's.show', $entityInstance) }}"
        class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6a9.77 9.77 0 018.7 5.47c.2.38.2.82 0 1.2A9.77 9.77 0 0112 18a9.77 9.77 0 01-8.7-5.47 1.23 1.23 0 010-1.2A9.77 9.77 0 0112 6zm0 4a2 2 0 100 4 2 2 0 000-4z" />
        </svg>
    </a>
</td>
