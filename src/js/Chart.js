const ctx = document.getElementById('myChart').getContext('2d');

async function renderChart(ctx) {
    let myChart;

    // Fetch data using AJAX
    let { data: rawData } = await $.ajax({
        url: '../ajax.php?action=ChartData',
        method: 'GET',
        dataType: 'json'
    });

    // Get the canvas context


    try {
        // Extract labels, data, and onclick elements from rawData
        const labels = rawData.map(item => item.label);
        const chartData = rawData.map(item => item.value);
        const onclickElements = rawData.map(item => item.onclickElement); // Capture onclickElement

        if (!renderChart.myChartInstance) {
            // Initialize the chart if it doesn't exist
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Use extracted labels
                    datasets: [{
                        label: '',
                        data: chartData, // Use extracted values
                        borderWidth: 1,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)'
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `# of Data: ${context.raw}`;
                                }
                            }
                        }
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const elementIndex = elements[0].index;

                            // Dynamically retrieve the onclickElement value
                            const elementId = onclickElements[elementIndex];
                            if (elementId) {
                                dashboard_tab(elementId); // Perform action
                            }
                        }
                    }
                }
            });

            // Store the chart instance
            renderChart.myChartInstance = myChart;
        } else {
            // Update the chart if it already exists
            myChart = renderChart.myChartInstance;
            myChart.data.labels = labels; // Update labels
            myChart.data.datasets[0].data = chartData; // Update data
            myChart.update(); // Refresh the chart
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

