document.addEventListener('DOMContentLoaded', function () {
    for (const [departament, departamentData] of Object.entries(arrayProduction)) {
        for (const [noParte, record] of Object.entries(departamentData)) {
            const data = record;
            const ctx = document.getElementById(`chart_${departament}_${noParte}`).getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.label,
                    datasets: [{
                        label: 'Cantidad Producida',
                        data: data.data,
                        backgroundColor: '#3f83f8',
                        borderColor: '#3f83f8',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Date Recorded'
                            }
                        },
                        y: {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Quantity'
                            }
                        }
                    }
                }
            });
        }
    }
});
