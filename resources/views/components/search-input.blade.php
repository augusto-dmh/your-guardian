<div class="max-w-lg pb-8">
    <label for="default-search" class="mb-2 font-medium text-gray-900 sr-only">Search</label>
    <div class="relative">
        <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
            <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
        </div>
        <div>
            <input type="search" name="searchTerm" value="{{ $searchTerm }}"
                class="block w-full p-4 text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-secondary-bg ps-10 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg"
                placeholder="{{ __('Search for title or description') }}" />
        </div>
        <button type="submit"
            class="right-3 absolute end-2.5 bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">Search</button>
    </div>
</div>
