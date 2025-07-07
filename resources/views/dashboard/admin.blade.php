@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
<div class="container py-4">
    @if(Auth::check())
        <h2 class="mb-4">📊 Dashboard - Key Performance Indicators</h2>
        <div class="row">
        <!-- Active Work Orders -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Active Work Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeOrders ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-check fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-danger h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Low Stock Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStock ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-circle fs-3 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Ink Formules -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ink Formulations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $formules ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-droplet-half fs-3 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completed Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedToday ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fs-3 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="row">
            <!-- Monthly Sales -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">📈 Monthly Sales by Year</h5>
                        <div id="chartContainer" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Top Clients -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">🏆 Top Clients per Year</h5>
                            <select id="yearSelector" class="form-select form-select-sm" style="width: auto;">
                                <!-- Se llena dinámicamente -->
                            </select>
                        </div>
                        <div id="chartContainer1" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>
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




