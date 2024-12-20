<div class="relative flex flex-col">
    <label for="{{ $field['name'] }}"
        class="cursor-pointer text-secondary-txt">{{ __($field['exhibitionName']) }}:</label>
    <input type="date"
        name="{{ $field['name'] }}"
        value="{{ old($field['name'], $field['value']->format('Y-m-d')) }}"
        id="{{ $field['name'] }}"
        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 focus:outline-none focus:ring-2 focus:ring-quinary-bg">
    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none top-6">
        <svg class="w-5 h-5 text-gray-300"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
            </path>
        </svg>
    </div>
    @error($field['name'])
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
