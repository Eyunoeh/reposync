

async function renderChart(canvas_ID, renderChart, ChartTitle) {
    let myChart;
    const ctx = document.getElementById(canvas_ID).getContext('2d');
    // Fetch data using AJAX
    let { data: rawData } = await $.ajax({
        url: '../ajax.php?action=ChartData&renderChartData=' +  renderChart,
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
                        backgroundColor:[
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor:  [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ]
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: ChartTitle,
                            position: 'bottom'
                        },
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

