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
        <canvas id="dataChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('dataChart').getContext('2d');
        let dataChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: @json($datasetLabel),
                    data: @json($data),
                }]
            }
        });

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
                    dataChart.data.labels = data.labels;
                    dataChart.data.datasets[0].data = data.data;
                    dataChart.data.datasets[0].label = data.datasetLabel;
                    dataChart.update();
                });
        }

        document.getElementById('data-type').addEventListener('change', updateChart);
        document.getElementById('interval-type').addEventListener('change', updateChart);
        document.getElementById('interval-length').addEventListener('change', updateChart);
    </script>
</x-app-layout>

