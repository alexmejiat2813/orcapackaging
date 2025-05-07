@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
<div class="container py-4">
    @if(Auth::check())
        <div class="alert alert-primary d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Welcome, {{ Auth::user()->Users_Name }}</h2>
                <p class="mb-0">Administrative Tools Panel</p>
            </div>
        </div>  

        <section class="section mb-5">
            <h3 class="mb-3">Monthly Sales by Year</h3>
            <div id="chartContainer" style="width:100%; height:500px;"></div>
        </section>

        <section class="section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Top Clients per Year</h3>
                <div class="d-flex align-items-center">
                    <label for="yearSelector" class="me-2 mb-0 fw-semibold">Select Year:</label>
                    <select id="yearSelector" class="form-select form-select-sm" style="width: 120px;"></select>
                </div>
            </div>

            <div id="chartContainer1" style="width:100%; height:500px;"></div>
        </section>
     @else
        <div class="alert alert-warning">
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="/assets/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="/assets/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="/assets/jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="/assets/jqwidgets/jqxchart.core.js"></script>
    <script type="module">
    import { TopClientsChart } from '/assets/js/topClientsChart.js';

    document.addEventListener('DOMContentLoaded', function () {
        new TopClientsChart('chartContainer1', 'yearSelector', '{{ url("/dashboard/chart/top-clients") }}');
    });
</script>
<script>
    $(document).ready(function () {
        fetch('/dashboard/chart')
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                // Transformar los datos en formato por mes, por año
                const grouped = {};

                data.forEach(item => {
                    const year = item.year_invoice;
                    const month = parseInt(item.month_invoice);
                    const total = parseFloat(item.total);

                    if (!grouped[year]) {
                        grouped[year] = Array(12).fill(null);
                    }
                    grouped[year][month - 1] = total;
                });

                const months = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ];

                // Convertir en formato esperado por jqxChart
                const chartData = months.map((month, index) => {
                    const row = { Month: month };
                    for (const year in grouped) {
                        row[year] = grouped[year][index] ?? 0;
                    }
                    return row;
                });

                const years = Object.keys(grouped);

                const settings = {
                    title: "Monthly Sales by Year",
                    description: "Total per month grouped by year",
                    enableAnimations: true,
                    showLegend: true,
                    padding: { left: 10, top: 10, right: 15, bottom: 10 },
                    titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                    source: chartData,
                    colorScheme: 'scheme05',
                    xAxis: {
                        dataField: 'Month',
                        unitInterval: 1,
                        tickMarks: { visible: true, interval: 1 },
                        gridLinesInterval: { visible: true, interval: 1 },
                        valuesOnTicks: false,
                        padding: { bottom: 10 }
                    },
                    valueAxis: {
                        unitInterval: 50000,
                        minValue: 0,
                        title: { text: 'Total (CAD)<br><br>' },
                        labels: { horizontalAlignment: 'right',
        formatSettings: {
            prefix: '$',
            decimalPlaces: 0,
            thousandsSeparator: ','
        } }
                    },
                    seriesGroups: [
                        {
                            type: 'line',
                            series: years.map(year => ({
                                dataField: year,
                                displayText: year,
                                symbolType: 'square'
                            }))
                        }
                    ]
                };

                $('#chartContainer').jqxChart(settings);
            })
            .catch(error => {
                console.error("Chart loading failed:", error);
                alert("Unable to load chart data.");
            });

    });
</script>

@endpush
