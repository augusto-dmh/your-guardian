<x-app-layout>
    <a type="button" href="{{ route('bills.create') }}"
        class="inline-block px-4 py-1 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Create') }}</a>

    <x-slot name="header">
        <h2 class="text-4xl font-bold text-secondary-txt">{{ __('Bills') }}</h2>
    </x-slot>

    <form method="GET" action="{{ route('bills.index') }}">
        <div class="flex items-center gap-8 py-6 m-auto">
            <div class="flex items-center gap-4">
                <h5 class="font-semibold text-primary-txt">{{ __('Sort by') }}:</h5>
                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Amount') }}</p>
                    <select name="sortByAmount"
                        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option class="font-thin" value="asc"
                            {{ request('sortByAmount') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
                        </option>
                        <option class="font-thin" value="desc"
                            {{ request('sortByAmount') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <p class="mb-1 text-secondary-txt">{{ __('Due Date') }}</p>
                    <select name="sortByDueDate"
                        class="font-thin border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option class="font-thin" value="asc"
                            {{ request('sortByDueDate') == 'asc' ? 'selected' : '' }}>{{ __('Ascending') }}
                        </option>
                        <option class="font-thin" value="desc"
                            {{ request('sortByDueDate') == 'desc' ? 'selected' : '' }}>{{ __('Descending') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="flex gap-8">
                <div class="flex items-center gap-4">
                    <h5 class="font-semibold text-primary-txt">{{ __('Filter by') }}:</h5>
                    <div class="form-group">
                        <p class="mb-1 text-secondary-txt">{{ __('Status') }}</p>
                        <div class="flex flex-col">
                            <label
                                class="inline-flex items-center cursor-pointer text-tertiary-txt hover:text-secondary-txt">
                                <input type="checkbox" name="filterByStatus[]" value="pending"
                                    class="border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg text-tertiary-txt bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg"
                                    {{ is_array(request('filterByStatus')) && in_array('pending', request('filterByStatus')) ? 'checked' : '' }}>
                                <span class="ml-2 font-thin">{{ __('Pending') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="px-4 py-1 shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">Apply</button>
        </div>
    </form>

    <div class="w-full overflow-x-auto rounded-lg">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-secondary-bg">
                <tr>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Amount') }}
                    </th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Title') }}
                    </th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Description') }}
                    </th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Date') }}
                    </th>
                    <th class="p-3 text-xs font-medium tracking-wider text-left uppercase text-primary-txt">
                        {{ __('Status') }}
                    </th>
                    <th class="p-3 text-xs font-medium tracking-wider text-center uppercase text-primary-txt">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-tertiary-bg">
                @foreach ($bills as $bill)
                    <tr
                        class="{{ $loop->iteration % 2 == 0 ? 'text-tertiary-txt bg-secondary-bg' : 'text-secondary-txt bg-tertiary-bg' }}">
                        <td class="p-3 font-semibold whitespace-nowrap">{{ $bill->amount }}</td>
                        <td class="p-3 font-normal whitespace-nowrap">{{ $bill->title }}</td>
                        <td class="p-3 font-normal whitespace-nowrap">
                            {{ Str::limit($bill->description, 20, '...') }}</td>
                        <td class="p-3 font-normal whitespace-nowrap">
                            {{ $bill->due_date->format('Y-m-d') }}</td>
                        <td class="p-3 font-normal whitespace-nowrap">{{ $bill->status }}</td>
                        <td class="flex items-center justify-center p-3 font-normal whitespace-nowrap">
                            <form action="{{ route('bills.destroy', $bill) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="block rounded-full cursor-pointer hover:shadow-inner text-tertiary-txt hover:text-secondary-txt">
                                    <svg class="w-8 h-8 p-1 rounded-full hover:text-secondary-txt" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('bills.edit', $bill) }}"
                                class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 21H7v-5L16.732 3.196a2.5 2.5 0 01-1.5-.964z" />
                                </svg>
                            </a>
                            <a href="{{ route('bills.show', $bill) }}"
                                class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6a9.77 9.77 0 018.7 5.47c.2.38.2.82 0 1.2A9.77 9.77 0 0112 18a9.77 9.77 0 01-8.7-5.47 1.23 1.23 0 010-1.2A9.77 9.77 0 0112 6zm0 4a2 2 0 100 4 2 2 0 000-4z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $bills->links() }}
</x-app-layout>
