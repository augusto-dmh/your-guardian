<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="p-6 m-auto rounded-sm shadow-inner form-wrapper h-fit w-fit">
        <div class="w-20 h-20 m-auto">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-full h-full" />
            </a>
        </div>

        <form action="{{ route('bills.update', $bill) }}" method="post">
            @csrf
            @method('PUT')

            <fieldset class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label for="title" class="cursor-pointer text-secondary-txt">{{ __('Title') }}:</label>
                    <input type="text" name="title" placeholder="Title" value="{{ $bill->title }}" id="title"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                </div>
                <div class="flex flex-col gap-1">
                    <label for="amount" class="cursor-pointer text-secondary-txt">{{ __('Amount') }}:</label>
                    <input type="text" name="amount" placeholder="Amount" value="{{ $bill->amount }}" id="amount"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                </div>
                <div class="relative flex flex-col">
                    <label for="due_date" class="cursor-pointer text-secondary-txt">{{ __('Due date') }}:</label>
                    <input type="date" name="due_date" value="{{ $bill->due_date->format('Y-m-d') }}" id="due_date"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none top-6">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="description" class="cursor-pointer text-secondary-txt">{{ __('Description') }}:</label>
                    <textarea id="description" name="description" rows="4" cols="50"
                        class="block w-full text-gray-300 bg-opacity-50 border-none rounded-lg appearance-none bg-tertiary-bg ps-4 pe-[4.75rem] focus:outline-none focus:ring-2 focus:ring-quinary-bg">{{ $bill->description }}</textarea>
                </div>
                <div class="flex flex-col gap-1">
                    <label for="status" class="cursor-pointer text-secondary-txt">{{ __('Status') }}:</label>
                    <select name="status" id="status"
                        class="font-thin text-gray-300 border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-quinary-bg bg-secondary-bg hover:bg-tertiary-bg focus:bg-tertiary-bg">
                        <option value="pending" {{ $bill->status === 'pending' ? 'selected' : '' }}>
                            {{ __('Pending') }}</option>
                        <option value="paid" {{ $bill->status === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}
                        </option>
                        <option value="overdue" {{ $bill->status === 'overdue' ? 'selected' : '' }}>
                            {{ __('Overdue') }}</option>
                    </select>
                </div>
            </fieldset>

            <div class="flex gap-3 mt-8">
                <button type="submit"
                    class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Update') }}</button>
                <a href="{{ route('bills.index') }}"
                    class="right-3 text-center w-full bottom-2.5 bg-primary-bg shadow-inner hover:shadow-innerHover text-tertiary-txt font-medium rounded-lg text-sm px-4 py-2">{{ __('Back') }}</a>
            </div>
        </form>
    </div>

</x-app-layout>
