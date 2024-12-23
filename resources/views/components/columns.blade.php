@php
    use Illuminate\Support\Str;

    $attributes = null;

    switch ($modelName) {
        case 'transaction':
            $attributes = (new App\Models\Transaction())->getFillable();
            break;
        case 'bill':
            $attributes = (new App\Models\Bill())->getFillable();
            break;
        case 'task':
            $attributes = (new App\Models\Task())->getFillable();
            break;
    }

    $attributeMapping = [
        'bill_id' => __('bill'),
        'transaction_category_id' => __('category'),
        'task_category_id' => __('category'),
        'created_at' => __('created at'),
        'paid_at' => __('paid at'),
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
