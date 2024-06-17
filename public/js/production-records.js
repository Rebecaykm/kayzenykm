document.addEventListener('DOMContentLoaded', function () {
    for (const [lineName, workCenters] of Object.entries(arrayProduction)) {
        for (const [workCenter, data] of Object.entries(workCenters)) {
            const ctx = document.getElementById(`chart_${lineName}_${workCenter}`).getContext('2d');
            const datasets = data.datasets.map((dataset, index) => ({
                ...dataset,
                backgroundColor: colors[index % colors.length],
                borderColor: colors[index % colors.length]
            }));

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Fecha y Hora'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Cantidad'
                            }
                        }
                    }
                }
            });
        }
    }
});
