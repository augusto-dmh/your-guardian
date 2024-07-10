<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="dark:text-[#fac189] text-xl font-bold leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're logged in!") }}
    </div>

    <div class="mb-4">
        <label for="data-type">Select interval type:</label>
        <select id="data-type" name="data-type">
            <option value="transactions" selected>Transactions</option>
            <option value="bills">Bills</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="interval-type">Select interval length:</label>
        <select id="interval-type" name="interval-type">
            <option value="year" selected>Yearly</option>
            <option value="month">Monthly</option>
            <option value="day">Daily</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="interval-length">Select Data Range Limit:</label>
        <select id="interval-length" name="interval-length">
            <option value="1" selected>One year</option>
            <option value="5">Five Years</option>
            <option value="10">Ten Years</option>
        </select>
    </div>

    <div style="width: 75%; margin: auto;">
        {!! $chart->container() !!}
    </div>

    <x-wallet class="flex-1" />
    <x-latest-transactions class="flex-1" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {!! $chart->script() !!}

    <script>
        function updateChart() {
            const dataType = document.getElementById('data-type').value;
            const interval = {
                type: document.getElementById('interval-type').value,
                length: document.getElementById('interval-length').value
            };

            fetch(`/api/charts/${dataType}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(interval),
                })
                .then(response => response.json())
                .then(data => {
                    window.{{ $chart->id }}.data.labels = data.labels;
                    window.{{ $chart->id }}.data.datasets[0].data = data.data;
                    window.{{ $chart->id }}.data.datasets[0].label = data.label;
                    window.{{ $chart->id }}.update();
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
