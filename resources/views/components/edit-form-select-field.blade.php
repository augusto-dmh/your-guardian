<div class="flex flex-col gap-1">
    <label for="{{$field['name']}}"
        class="cursor-pointer text-secondary-txt">{{ __($field['exhibitionName']) }}:</label>
    <select id="{{$field['name']}}"
        name="{{$field['name']}}"
        class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
        @foreach($field['options'] as $option)
            <option value="{{ $option['value'] }}"
                {{ old($field['name'], $field['value']) == $option['value'] ? 'selected' : '' }}>
                {{ $option['label'] }}
            </option>
        @endforeach
    </select>
    @error($field['name'])
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
