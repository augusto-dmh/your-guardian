<div class="flex flex-col gap-1">
    <label for="{{$field['name']}}"
        class="cursor-pointer text-secondary-txt">{{ __($field['exhibitionName']) }}:</label>
    <input type="text"
        name="{{$field['name']}}"
        placeholder="{{$field['exhibitionName']}}"
        value="{{ old($field['name'], $field['value']) }}"
        id="{{$field['name']}}"
        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
    @error($field['name'])
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
