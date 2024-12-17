<div class="flex items-end gap-4">
    <div class="flex items-end gap-2">
        @foreach($fields as $field)
            <x-select-sort :field="$field" />
        @endforeach
    </div>
</div>
