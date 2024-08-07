@props(['entity'])

@php
    use Illuminate\Support\Str;

    $attributes = null;

    switch ($entity) {
        case 'App\Models\Transaction':
            $attributes = (new App\Models\Transaction())->getFillable();
            break;
        case 'App\Models\Bill':
            $attributes = (new App\Models\Bill())->getFillable();
            break;
        case 'App\Models\Task':
            $attributes = (new App\Models\Task())->getFillable();
            break;
    }

    $attributeMapping = [
        'bill_id' => 'bill',
        'transaction_category_id' => 'transaction category',
        'task_category_id' => 'task category',
    ];

    $transformAttribute = function ($attribute, $mapping) {
        if (array_key_exists($attribute, $mapping)) {
            return $mapping[$attribute];
        }

        return Str::replace('_', ' ', Str::snake($attribute));
    };

    $filteredAttributes = array_filter($attributes, function ($attribute) {
        return $attribute !== 'user_id';
    });
@endphp

<tr class="border-b">
    @foreach ($filteredAttributes as $attribute)
        <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
            {{ __(ucfirst($transformAttribute($attribute, $attributeMapping))) }}
        </th>
    @endforeach
    <th class="p-3 text-xs font-medium tracking-wider text-center uppercase text-primary-txt">
        {{ __('Actions') }}
    </th>
</tr>