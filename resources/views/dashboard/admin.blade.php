@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
<div class="container py-4">
    @if(Auth::check())
        <h2 class="mb-4">Dashboard - Key Performance Indicators</h2>

        {{-- KPI Cards --}}
        <div class="row">
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

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Open Purchase Orders</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $openPOs ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cart3 fs-3 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
        </div>

        <div class="row">
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

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-left-secondary h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedTotal ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-archive fs-3 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts (admin only) --}}
        @if($showFinancials ?? false)
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Sales by Year</h5>
                        <div id="chartContainer" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Top Clients per Year</h5>
                            <select id="yearSelector" class="form-select form-select-sm" style="width: auto;"></select>
                        </div>
                        <div id="chartContainer1" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        @endif

        {{-- Recent Active Orders --}}
        @if($recentOrders->isNotEmpty())
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Recent Active Orders (Ready for Production)</span>
                        <a href="/production/orders" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->Commande_Id }}</td>
                                    <td>{{ $order->InInvoiceNumber ?? '-' }}</td>
                                    <td>{{ $order->customer->Customer_Name ?? $order->Customer_Id }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $order->productionStatus->Production_Status_Description ?? 'Ready' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @else
        <div class="alert alert-warning">
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
        </div>
    @endif
</div>
@endsection


@push('scripts')
@if($showFinancials ?? false)
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
            .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
            .then(data => {
                const grouped = {};
                data.forEach(item => {
                    const year = item.year_invoice;
                    const month = parseInt(item.month_invoice);
                    const total = parseFloat(item.total);
                    if (!grouped[year]) grouped[year] = Array(12).fill(null);
                    grouped[year][month - 1] = total;
                });

                const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                const chartData = months.map((month, i) => {
                    const row = { Month: month };
                    for (const year in grouped) row[year] = grouped[year][i] ?? 0;
                    return row;
                });

                const years = Object.keys(grouped);
                $('#chartContainer').jqxChart({
                    title: 'Monthly Sales by Year',
                    description: 'Total per month grouped by year',
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
                        labels: {
                            horizontalAlignment: 'right',
                            formatSettings: { prefix: '$', decimalPlaces: 0, thousandsSeparator: ',' }
                        }
                    },
                    seriesGroups: [{
                        type: 'line',
                        series: years.map(year => ({ dataField: year, displayText: year, symbolType: 'square' }))
                    }]
                });
            })
            .catch(err => console.error('Chart loading failed:', err));
    });
</script>
@endif
@endpush
