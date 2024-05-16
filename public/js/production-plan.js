document.addEventListener('DOMContentLoaded', function() {
    for (const [departament, departamentData] of Object.entries(arrayPlan)) {
        for (const [planDate, shifts] of Object.entries(departamentData)) {
            for (const [shift, records] of Object.entries(shifts)) {
                const data = records;
                const ctx = document.getElementById(`chart_${departament}_${planDate}_${shift}`);
                if (ctx) {
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(data),
                            datasets: [{
                                label: 'Cantidad Planificada',
                                data: Object.values(data).map(entry => entry.planQuantity),
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Cantidad Producida',
                                data: Object.values(data).map(entry => entry.productionQuantity),
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                x: {
                                    display: true,
                                    scaleLabel: {
                                        beginAtZero: true,
                                        display: true,
                                    }
                                },
                                y: {
                                    display: true,
                                    scaleLabel: {
                                        beginAtZero: true,
                                        display: true,
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    }
});