<div class="flex items-end gap-4">
    <div class="form-group">
        @foreach ($fields as $field)
            <p class="mb-1 text-secondary-txt">{{ __($field['name']) }}</p>
            <div class="flex flex-row gap-4 sm:gap-0 sm:flex-col">
                @foreach($field['values'] as $fieldValue)
                    <x-checkbox-filter :fieldName="$field['name']" :fieldValue="$fieldValue" />
                @endforeach
            </div>
        @endforeach
    </div>
</div>
