// topClientsChart.js

export  class TopClientsChart {
    constructor(containerId, selectId, endpointUrl) {
        this.containerId = containerId;
        this.selectId = selectId;
        this.endpointUrl = endpointUrl;
        this.chartInstance = null;

        this.init();
    }

    init() {
        this.initYearSelector();
        this.fetchAndRenderChart();

        document.getElementById(this.selectId).addEventListener('change', () => {
            this.fetchAndRenderChart();
        });
    }

    initYearSelector() {
        const currentYear = new Date().getFullYear();
        const select = document.getElementById(this.selectId);

        for (let i = currentYear; i >= currentYear - 3; i--) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            select.appendChild(option);
        }
    }

    fetchAndRenderChart() {
        const selectedYear = document.getElementById(this.selectId).value;

        fetch(`${this.endpointUrl}?year=${selectedYear}`)
            .then(response => response.json())
            .then(data => {
                this.renderChart(data);
            })
            .catch(error => {
                console.error('Failed to load data:', error);
                alert('Unable to load chart data.');
            });
    }

    renderChart(data) {
    if (!data || data.length === 0) {
        console.warn('No data to display');
        return;
    }

    const yearData = data[0]; // single object
    const chartData = [];

    for (const [key, value] of Object.entries(yearData)) {
        if (key !== 'Year') {
            chartData.push({
                Client: key,
                Total: parseFloat(value)
            });
        }
    }

    const settings = {
        title: "Top 10 Clients",
        description: `Total sales for ${yearData.Year}`,
        enableAnimations: true,
        showLegend: false,
        padding: { left: 10, top: 10, right: 10, bottom: 10 },
        titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
        source: chartData,
        xAxis: {
            dataField: 'Client',
            gridLines: { visible: true },
        },
        valueAxis: {
            unitInterval: 50000,
            title: { text: 'Total Sales ($)' },
            labels: {
                formatFunction: (value) => `$${value.toLocaleString()}`
            }
        },
        colorScheme: 'scheme05',
        seriesGroups: [
            {
                type: 'column',
                series: [
                    { dataField: 'Total', displayText: 'Total Sales' }
                ]
            }
        ]
    };

    jqwidgets.createInstance(`#${this.containerId}`, 'jqxChart', settings);
}

}

// Example of usage
// new TopClientsChart('chartContainer', 'yearSelector', '/dashboard/chart/top-clients');
