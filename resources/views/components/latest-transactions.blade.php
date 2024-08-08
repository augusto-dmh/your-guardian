@php
    $user = auth()->user();
    $transactions = $user->transactions()->latest()->take(5)->with('transactionCategory')->get();
@endphp
<div
    {{ $attributes->merge(['class' => 'relative flex flex-col gap-2 shadow-out p-5 rounded-md [&>p]:text-primary-txt [&>p>span]:text-tertiary-txt']) }}>
    <h3 class="inline-block mb-1 text-xl font-bold text-secondary-txt">{{ __('Latest Transactions') }}</h3>
    <div class="shadow sm:rounded-lg">
        @if ($transactions->isNotEmpty())
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full divide-y divide-gray-200 ">
                    <thead class="bg-secondary-bg">
                        <tr>
                            <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                {{ __('Amount') }}
                            </th>
                            <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                {{ __('Title') }}
                            </th>
                            <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                {{ __('Category') }}
                            </th>
                            <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                {{ __('Date') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-tertiary-bg">
                        @foreach ($transactions as $transaction)
                            <tr
                                class="{{ $loop->iteration % 2 == 0 ? 'text-tertiary-txt bg-secondary-bg' : 'text-secondary-txt bg-tertiary-bg' }}">
                                <td class="p-3 text-sm font-semibold whitespace-nowrap">
                                    {{ $transaction->amount }}
                                </td>
                                <td class="p-3 text-sm font-normal whitespace-nowrap">
                                    {{ Str::limit($transaction->title, 20, '...') }}
                                </td>
                                <td class="p-3 text-sm font-normal whitespace-nowrap">
                                    {{ Str::limit($transaction->transactionCategory?->name, 10, '...') }}
                                </td>
                                <td class="p-3 text-sm font-normal whitespace-nowrap">
                                    {{ $transaction->created_at->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="absolute inset-0 z-10 flex items-center justify-center">
                <p class="text-4xl text-center select-none text-tertiary-bg">
                    {{ __('Waiting transactions...') }}
                </p>
            </div>
        @endif
    </div>
</div>
