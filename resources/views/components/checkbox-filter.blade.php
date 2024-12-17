<label for="input-type-{{ $fieldValue }}"
    class="inline-flex items-center font-thin cursor-pointer text-tertiary-txt hover:text-secondary-txt">
    <input type="checkbox" name="filterBy{{ $fieldName }}[]" id="input-type-{{ $fieldValue }}" value="{{ $fieldValue }}"
        class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
        {{ request("filterBy{$fieldName}") && in_array($fieldValue, request("filterBy{$fieldName}")) ? 'checked' : '' }}>
    <span class="ml-2">{{ __(ucfirst($fieldValue)) }}</span>
</label>
