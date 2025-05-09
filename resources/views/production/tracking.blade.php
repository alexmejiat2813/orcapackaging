@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>Order Tracking</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Order Tracking</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-3">
    <div class="col-md-4">
        <label for="filterInput">Filter by Order Number (InInvoiceNumber):</label>
        <input id="filterInput" type="text" class="form-control" placeholder="e.g. 5341" />
    </div>
</div>

    <section class="section mt-4">
        <div id="kanban"></div>
    </section>

         
 
@endsection

@push('scripts')
<script src="/assets/jqwidgets/jqxcore.js"></script>
    <script src="/assets/jqwidgets/jqxbuttons.js"></script>
    <script src="/assets/jqwidgets/jqxscrollbar.js"></script>
    <script src="/assets/jqwidgets/jqxlistbox.js"></script>
    <script src="/assets/jqwidgets/jqxdragdrop.js"></script>
    <script src="/assets/jqwidgets/jqxsortable.js"></script>
    <script src="/assets/jqwidgets/jqxdata.js"></script>
    <script src="/assets/jqwidgets/jqxkanban.js"></script>
<script type="module">
    import { TrackingKanban } from '/assets/js/trackingKanban.js';

    document.addEventListener('DOMContentLoaded', () => {
        new TrackingKanban('kanban', 'filterInput', '{{ url("/production/tracking/kanban") }}');
    });

   /* $(document).ready(function () {
        const fields = [
            { name: "id", type: "string" },
            { name: "status", map: "kanban_status", type: "string" },
            { name: "text", map: "label", type: "string" },
            { name: "tags", type: "string" },
            { name: "color", type: "string" },
            //{ name: "resourceId", type: "number" }
        ];

        fetch("{{ url('/production/tracking/kanban') }}")
            .then(response => response.json())
            .then(json => {
                const items = json.map((item, index) => ({
                    id: `${item.Lot_Id}`,
                    kanban_status: mapStatus(item.KANBAN_STATUS),
                    label: `Lot ${item.Lot_Id} - ${item.PrDescription1.substring(0, 100)} ${item.PrDescription2.substring(0, 100)}`,
                    tags: `${item.Customer_Code}, ${item.InInvoiceNumber}, ${item.PrNumber}`,
                    color: getColor(item.KANBAN_STATUS)
                    //resourceId: 0
                }));

                const source = {
                    localData: items,
                    dataType: "array",
                    dataFields: fields
                };

                const dataAdapter = new $.jqx.dataAdapter(source);

                $('#kanban').jqxKanban({
                    width: '100%',
                    height: 700,
                    source: dataAdapter,
                    columns: [
                        { text: "Backlog", dataField: "backlog" },
                        { text: "In Progress", dataField: "work" },
                        { text: "Partial", dataField: "partial" },
                        { text: "Done", dataField: "done" }
                    ]
                });
            });

        function mapStatus(status) {
            switch (status.toLowerCase()) {
                case 'backlog': return 'backlog';
                case 'in progress': return 'work';
                case 'partial': return 'partial';
                case 'done': return 'done';
                default: return 'backlog';
            }
        }

        function getColor(status) {
            switch (status.toLowerCase()) {
                case 'backlog': return '#5dc3f0';
                case 'in progress': return '#f19b60';
                case 'partial': return '#fedd69';
                case 'done': return '#6bbd49';
                default: return '#cccccc';
            }
        }
    });*/
</script>
@endpush
