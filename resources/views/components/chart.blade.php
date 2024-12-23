<canvas id="chart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script defer>
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
</script>
