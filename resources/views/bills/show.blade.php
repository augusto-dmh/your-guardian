<x-app-layout>
    @if (session('success'))
        <div id="flash-message"
            class="absolute z-30 p-4 text-white transition-opacity duration-1000 transform -translate-x-1/2 bg-green-500 rounded-md shadow-md left-1/2">
            {{ session('success') }}
        </div>
    @endif

    <x-slot name="header">
    </x-slot>

    <div
        class="absolute w-3/4 transform -translate-x-1/2 -translate-y-1/2 rounded-md shadow-inner sm:w-3/4 md:w-2/4 top-1/2 left-1/2 bg-secondary-bg">
        <div class="flex flex-col justify-center p-6">

            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-4xl font-bold text-secondary-txt">Bill #{{ $bill->id }}</h2>
                    <div class="flex items-center">
                        <a href="{{ route('bills.edit', ['bill' => $bill]) }}"
                            class="block rounded-full text-tertiary-txt hover:shadow-inner hover:text-secondary-txt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 hover:text-secondary-txt"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L12 21H7v-5L16.732 3.196a2.5 2.5 0 01-1.5-.964z" />
                            </svg>
                        </a>
                        <form action="{{ route('bills.destroy', ['bill' => $bill]) }}" method="POST">
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
                        @if ($bill->status !== 'paid')
                            <form action="{{ route('bills.update', ['bill' => $bill]) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="paid">
                                <button type="submit" title="{{ __("Change status to 'paid'") }}"
                                    class="block rounded-full cursor-pointer hover:shadow-inner text-tertiary-txt hover:text-secondary-txt">
                                    <svg class="w-8 h-8 p-1 rounded-full hover:text-secondary-txt" viewBox="0 0 24 24"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M13 3.5C13 2.94772 12.5523 2.5 12 2.5C11.4477 2.5 11 2.94772 11 3.5V4.0592C9.82995 4.19942 8.75336 4.58509 7.89614 5.1772C6.79552 5.93745 6 7.09027 6 8.5C6 9.77399 6.49167 10.9571 7.5778 11.7926C8.43438 12.4515 9.58764 12.8385 11 12.959V17.9219C10.2161 17.7963 9.54046 17.5279 9.03281 17.1772C8.32378 16.6874 8 16.0903 8 15.5C8 14.9477 7.55228 14.5 7 14.5C6.44772 14.5 6 14.9477 6 15.5C6 16.9097 6.79552 18.0626 7.89614 18.8228C8.75336 19.4149 9.82995 19.8006 11 19.9408V20.5C11 21.0523 11.4477 21.5 12 21.5C12.5523 21.5 13 21.0523 13 20.5V19.9435C14.1622 19.8101 15.2376 19.4425 16.0974 18.8585C17.2122 18.1013 18 16.9436 18 15.5C18 14.1934 17.5144 13.0022 16.4158 12.1712C15.557 11.5216 14.4039 11.1534 13 11.039V6.07813C13.7839 6.20366 14.4596 6.47214 14.9672 6.82279C15.6762 7.31255 16 7.90973 16 8.5C16 9.05228 16.4477 9.5 17 9.5C17.5523 9.5 18 9.05228 18 8.5C18 7.09027 17.2045 5.93745 16.1039 5.17721C15.2467 4.58508 14.1701 4.19941 13 4.0592V3.5ZM11 6.07814C10.2161 6.20367 9.54046 6.47215 9.03281 6.8228C8.32378 7.31255 8 7.90973 8 8.5C8 9.22601 8.25834 9.79286 8.79722 10.2074C9.24297 10.5503 9.94692 10.8384 11 10.9502V6.07814ZM13 13.047V17.9263C13.7911 17.8064 14.4682 17.5474 14.9737 17.204C15.6685 16.7321 16 16.1398 16 15.5C16 14.7232 15.7356 14.1644 15.2093 13.7663C14.7658 13.4309 14.0616 13.1537 13 13.047Z" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="overflow-auto break-all max-h-32">{{ $bill->description }}</div>
            </div>

            <div class="flex flex-col gap-3 [&>div>span]:text-tertiary-txt">

                <div class="flex gap-2">
                    <span>Title:</span>
                    <div>{{ $bill->title }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Amount:</span>
                    <div>{{ $bill->amount }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Category:</span>
                    <div>{{ $bill->billCategory?->name ?? 'none' }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Status</span>
                    <div>{{ $bill->status }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Created at:</span>
                    <div>{{ $bill->created_at->format('m-d-Y') }}</div>
                </div>

                <div class="flex gap-2">
                    <span>Due date:</span>
                    <div>{{ $bill->due_date->format('m-d-Y') }}</div>
                </div>

                @if ($bill->paid_at)
                    <div class="flex gap-2">
                        <span>Paid at</span>
                        <div>{{ $bill->paid_at->format('m-d-Y') }}</div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
