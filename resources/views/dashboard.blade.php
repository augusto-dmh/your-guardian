<x-app-layout>
    @if (auth()->user()->billsPercentagePerStatus['overdue'] > 0)
        <div class="fixed z-10 px-4 py-2 text-2xl transform -translate-x-1/2 rounded-md shadow-inner top-6 left-1/2 text-tertiary-txt bg-primary-bg"
            role="alert">
            {{ __('Attention: you have overdue bills.') }} <a class="shadow-inner text-secondary-txt hover:underline"
                href="{{ route('bills.index', ['filterByStatus' => ['overdue'], 'sortByDueDate' => 'desc']) }}">{{ __('Check them') }}!</a>
        </div>
    @endif

    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <h2 class="text-[#fac189] text-xl font-bold leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="flex flex-col justify-center gap-4">
        <div class="flex item flex-col gap-4 w-full lg:justify-between lg:flex-row [&>div]:text-center [&>div]:w-full">
            <x-dashboard-card title="{{ __('Bills Status') }}">
                <div class="flex gap-3">
                    <div class="flex">
                        {{ auth()->user()->billsPercentagePerStatus['pending'] . '%' }}<x-heroicon-o-clock
                            class="w-6 h-6 text-yellow-500" /></div>
                    <div class="flex">
                        {{ auth()->user()->billsPercentagePerStatus['paid'] . '%' }}<x-heroicon-o-check-circle
                            class="w-6 h-6 text-green-500" />
                    </div>
                    <div class="flex">
                        {{ auth()->user()->billsPercentagePerStatus['overdue'] . '%' }}<x-heroicon-o-x-circle
                            class="w-6 h-6 text-red-500" />
                    </div>
                </div>
            </x-dashboard-card>
            <x-dashboard-card title="{{ __('Transactions Types') }}">
                <div class="flex gap-3">
                    <div class="flex">
                        {{ auth()->user()->transactionsPercentagePerType['income'] . '%' }}<x-heroicon-o-trending-up
                            class="w-6 h-6 text-green-500" />
                    </div>
                    <div class="flex">
                        {{ auth()->user()->transactionsPercentagePerType['expense'] . '%' }}<x-heroicon-o-trending-down
                            class="w-6 h-6 text-red-500" />
                    </div>
                </div>
            </x-dashboard-card>
            <x-dashboard-card title="{{ __('Top Transaction Category') }}">
                <span>{{ auth()->user()->transactionCategoryWithMostTransactions }}</span>
            </x-dashboard-card>
        </div>

        <div class="flex flex-col gap-4 lg:flex-row">
            <div class="flex flex-col flex-1 dashboard-grid-container">
                <div class="">
                    <div class="flex items-end justify-between gap-4 md:flex-row">
                        <div class="flex-1 mb-4 text-center">
                            <label for="select-data-type" class="block w-full">{{ __('Data Type') }}</label>
                            <select id="select-data-type" name="data-type"
                                class="w-full focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                                <option value="transactions" selected>{{ __('Transactions') }}</option>
                                <option value="bills">{{ __('Bills') }}</option>
                            </select>
                        </div>

                        <div class="flex-1 mb-4 text-center">
                            <label id="label-type-or-status" for="select-type-or-status"
                                class="block w-full">{{ __('Type') }}</label>
                            <select id="select-type-or-status" name="type"
                                class="w-full focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                                <option value="income" selected>{{ __('Income') }}</option>
                                <option value="expense">{{ __('Expense') }}</option>
                            </select>
                        </div>

                        <div class="flex-1 mb-4 text-center">
                            <label for="select-length" class="block w-full">{{ __('Length') }}</label>
                            <select id="select-length" name="length"
                                class="w-full focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                                <option value="7" selected>{{ '7 ' . __('Days') }}</option>
                                <option value="14">{{ '14 ' . __('Days') }}</option>
                                <option value="28">{{ '28 ' . __('Days') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="relative flex-1 shadow-inner">
                    @if (!Auth::user()->has_transactions_or_paid_bills)
                        <div class="absolute inset-0 z-10 flex items-center justify-center">
                            <p class="text-5xl text-center select-none text-tertiary-bg">
                                {{ __('Waiting data...') }}
                            </p>
                        </div>
                    @endif
                    <canvas id="chart"></canvas>
                </div>
            </div>

            <div class="flex flex-col flex-1 gap-4">
                <x-wallet class="flex-1" />
                <x-latest-transactions class="flex-1" />
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const selectDataType = document.getElementById('select-data-type');
        const selectTypeOrStatus = document.getElementById('select-type-or-status');
        const selectLength = document.getElementById('select-length');
        const labelSelectTypeOrStatus = document.querySelector('#label-type-or-status');

        const ctx = document.getElementById('chart');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    backgroundColor: '#FAC189',
                    borderColor: '#FAC189',
                }],
            },
            options: {
                maintainAspectRatio: false
            }
        });
        updateChart();

        function updateChart() {
            const dataType = selectDataType.value;
            const body = {
                type: selectTypeOrStatus.value,
                length: selectLength.value
            };

            fetch(`/api/chart-data/${dataType}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(body),
                })
                .then(response => response.json())
                .then(data => {
                    chart.data.labels = data.labels;
                    chart.data.datasets[0].data = data.dataset.data;
                    chart.data.datasets[0].label = data.dataset.label;
                    chart.data.datasets[0].showLine = data.dataset.showLine;
                    chart.update();
                });
        }

        selectDataType.addEventListener('change', () => {
            const selectTypeOptions = {
                bills: [{
                        value: 'pending',
                        text: '{{ __('Pending') }}'
                    },
                    {
                        value: 'paid',
                        text: '{{ __('Paid') }}'
                    },
                ],
                transactions: [{
                        value: 'income',
                        text: '{{ __('Income') }}'
                    },
                    {
                        value: 'expense',
                        text: '{{ __('Expense') }}'
                    },
                ]
            };

            // clear selectTypeOrStatus
            selectTypeOrStatus.innerHTML = '';

            selectDataType.value === 'transactions' ?
                labelSelectTypeOrStatus.innerHTML = "{{ __('Type') }}" :
                labelSelectTypeOrStatus.innerHTML = "{{ __('Status') }}";

            // get options for selectTypeOrStatus based on dataType
            const selectTypeNewOptions = selectTypeOptions[selectDataType.value];

            // populate selectTypeOrStatus with new options
            selectTypeNewOptions.forEach((option, index) => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.text;

                if (index === 0) {
                    optionElement.selected = true;
                }

                selectTypeOrStatus.appendChild(optionElement);
            });

            updateChart();
        });
        selectTypeOrStatus.addEventListener('change', updateChart);
        selectLength.addEventListener('change', updateChart);
    </script>
</x-app-layout>
