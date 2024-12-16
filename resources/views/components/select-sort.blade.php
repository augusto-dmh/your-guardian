<div class="form-group">
    <p class="mb-1 text-secondary-txt">{{ __("{$field}") }}</p>
    <select name="sortBy{{ $field }}"
        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
        <option class="font-thin" value="">
            {{ __('Sort by') }}
        </option>
        <option class="font-thin" value="asc"
            {{ request("sortBy{$field}") == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
        </option>
        <option class="font-thin" value="desc"
            {{ request("sortBy{$field}") == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
        </option>
    </select>
</div>
