<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="dark:text-[#fac189] text-xl font-bold leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="flex flex-row min-w-0 gap-6 p-4">
        <div class="flex flex-col flex-1 dashboard-grid-container">
            <div class="flex flex-row justify-between text-center">
                <div class="mb-4">
                    <label for="data-type" class="block">{{ __('Data Type') }}</label>
                    <select id="data-type" name="data-type"
                        class="focus:outline-none focus:ring-2 focus:ring-quinary-bg max-w-38">
                        <option value="transactions" selected>{{ __('Transactions') }}</option>
                        <option value="bills">{{ __('Bills') }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="interval-type" class="block">{{ __('Interval Type') }}</label>
                    <select id="interval-type" name="interval-type"
                        class="focus:outline-none focus:ring-2 focus:ring-quinary-bg max-w-38">
                        <option value="yearly" selected>{{ __('Yearly') }}</option>
                        <option value="monthly">{{ __('Monthly') }}</option>
                        <option value="daily">{{ __('Daily') }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="interval-length" class="block">{{ __('Interval Length') }}</label>
                    <select id="interval-length" name="interval-length"
                        class="focus:outline-none focus:ring-2 focus:ring-quinary-bg max-w-38">
                        <option value="1" selected>{{ __('One year') }}</option>
                        <option value="5">{{ __('Five Years') }}</option>
                        <option value="10">{{ __('Ten Years') }}</option>
                    </select>
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

        <div class="flex flex-col flex-1 gap-8">
            <x-wallet class="flex-1" />
            <x-latest-transactions class="flex-1" />
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
