<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <h2 class="dark:text-[#fac189] text-xl font-bold leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="flex flex-col justify-center gap-4">
        <div class="flex item flex-col gap-4 w-full lg:justify-between lg:flex-row [&>div]:text-center [&>div]:w-full">
            <x-dashboard-card title="{{ __('Bills Status (%)') }}" :data="auth()->user()->billsPercentagePerStatus" />
            <x-dashboard-card title="{{ __('Transactions Types (%)') }}" :data="auth()->user()->transactionsPercentagePerType" />
            <x-dashboard-card title="{{ __('Top Transaction Category') }}" :data="auth()->user()->transactionCategoryWithMostTransactions" />
        </div>

        <div class="flex flex-col gap-4 lg:flex-row">
            <div class="flex flex-col flex-1 dashboard-grid-container">
                <div class="">
                    <div class="flex items-end justify-between gap-4 md:flex-row">
                        <div class="flex-1 mb-4 text-center">
                            <label for="data-type" class="block w-full">{{ __('Data Type') }}</label>
                            <select id="data-type" name="data-type"
                                class="w-full focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                                <option value="transactions" selected>{{ __('Transactions') }}</option>
                                <option value="bills">{{ __('Bills') }}</option>
                            </select>
                        </div>

                        <div class="flex-1 mb-4 text-center">
                            <label for="interval-type" class="block w-full">{{ __('Interval') }}</label>
                            <select id="interval-type" name="interval-type"
                                class="w-full focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                                <option value="yearly" selected>{{ __('Yearly') }}</option>
                                <option value="monthly">{{ __('Monthly') }}</option>
                                <option value="daily">{{ __('Daily') }}</option>
                            </select>
                        </div>

                        <div class="flex-1 mb-4 text-center">
                            <label for="interval-length" class="block w-full">{{ __('Length') }}</label>
                            <select id="interval-length" name="interval-length"
                                class="w-full focus:outline-none focus:ring-2 focus:ring-quinary-bg">
                                <option value="1" selected>{{ __('One year') }}</option>
                                <option value="5">{{ __('Five Years') }}</option>
                                <option value="10">{{ __('Ten Years') }}</option>
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
            const dataType = document.getElementById('data-type').value;
            const interval = {
                type: document.getElementById('interval-type').value,
                length: document.getElementById('interval-length').value
            };

            fetch(`/api/chart-data/${dataType}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(interval),
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

        document.getElementById('data-type').addEventListener('change', updateChart);
        document.getElementById('interval-type').addEventListener('change', updateChart);
        document.getElementById('interval-length').addEventListener('change', updateChart);
        document.querySelector('.transactions-total-paid-btn').addEventListener('click', () => {
            document.getElementById('data-type').selectedIndex = 0;
            updateChart();
        });
        document.querySelector('.bills-n-of-paid-btn').addEventListener('click', () => {
            document.getElementById('data-type').selectedIndex = 1;
            updateChart();
        });
    </script>
</x-app-layout>
