@php
    $user = auth()->user();
    $transactions = $user->transactions()->latest()->take(5)->with('transactionCategory')->get();
@endphp

<div class="p-4 rounded-lg shadow-out">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-xl font-bold text-secondary-txt">Latest Transactions</h3>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('transactions.index') }}"
                class="p-2 text-sm font-medium rounded-lg shadow-inner hover:shadow-innerHover text-tertiary-txt hover:text-secondary-txt">View
                all</a>
        </div>
    </div>
    <div class="flex flex-col mt-6">
        <div class="overflow-x-auto rounded-lg">
            <div class="align-middle">
                <div class="shadow sm:rounded-lg">
                    <table class="w-full divide-y divide-gray-200 ">
                        <thead class="bg-secondary-bg">
                            <tr>
                                <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                    Amount
                                </th>
                                <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                    Description
                                </th>
                                <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                    Category
                                </th>
                                <th scope="col" class="p-3 text-xs font-medium tracking-wider text-left uppercase ">
                                    Date
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
                                        {{ Str::limit($transaction?->description, 10, '...') }}
                                    </td>
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
            </div>
        </div>
    </div>
</div>
</div>
