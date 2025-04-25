@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>Production Jobs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Production Jobs</li>
            </ol>
        </nav>
    </div>

    <!-- Tabla para mostrar los datos de Commandes -->
    <section class="section mt-4">
        <button id="backButton">⬅ Back to Order List</button>
        <div id="ordersGrid"></div>

        <div id="workOrderView" style="display: none">
            <h2>Uteco Production Work Order</h2>

            <div class="section-title">General Information</div>
            <div class="form-inline">
                <div><label for="startDate">Start Date:</label><div id="startDate"></div></div>
                <div><label for="cmdNumber">CMD#:</label><input id="cmdNumber" /></div>
                <div><label for="client">Client:</label><input id="client" /></div>
                <div><label for="repetition">Repetition:</label><div id="repetition"></div></div>
                <div><label for="productionTime">Production Time (hr):</label><div id="productionTime"></div></div>
                <div><label for="solventQty">Solvent (kg):</label><div id="solventQty"></div></div>
            </div>

            <div class="section-title">Ink Usage</div>
            <div class="grid-container">
                <div id="inkGrid"></div>
            </div>

            <div class="section-title">Printing Process</div>
            <div class="grid-container">
                <div id="printGrid"></div>
            </div>
        </div>
    </section>

@endsection

  <link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.base.css" type="text/css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f8ff;
      padding: 20px;
    }
    .section-title {
      background-color: #333;
      color: white;
      padding: 10px;
      margin-top: 30px;
      margin-bottom: 10px;
      font-weight: bold;
      text-transform: uppercase;
    }
    .form-inline {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .form-inline > div {
      display: flex;
      flex-direction: column;
    }
    .grid-container {
      margin-top: 20px;
      border: 1px solid #ccc;
      background: white;
      padding: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    #backButton {
      margin-bottom: 20px;
      display: none;
    }
  </style>
  @push('scripts')
    <script src="/assets/jqwidgets/jqxcore.js"></script>
    <script src="/assets/jqwidgets/jqxbuttons.js"></script>
    <script src="/assets/jqwidgets/jqxinput.js"></script>
    <script src="/assets/jqwidgets/jqxnumberinput.js"></script>
    <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
    <script src="/assets/jqwidgets/jqxdatetimeinput.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.edit.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.columnsresize.js"></script>
    <script src="/assets/jqwidgets/jqxdata.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.selection.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.pager.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.sort.js"></script>

    <script>
        $(document).ready(function () {
            const workOrderView = $('#workOrderView');
            const ordersGrid = $('#ordersGrid');
            const backButton = $('#backButton');

            const ordersData = new $.jqx.dataAdapter({
                dataType: 'array',
                dataFields: [
                    { name: 'orderCode', type: 'number' },
                    { name: 'customerCode', type: 'number' },
                    { name: 'customerName', type: 'string' },
                    { name: 'orderDate', type: 'date' },
                    { name: 'requestedDate', type: 'date' },
                    { name: 'clientPO', type: 'string' },
                    { name: 'lotId', type: 'number' },
                    { name: 'productNumber', type: 'string' },
                    { name: 'description', type: 'string' },
                    { name: 'quantity', type: 'number' }
                ],
                localData: [
                    {
                        orderCode: 3285,
                        customerCode: 5017,
                        customerName: 'Vibac Canada Inc',
                        orderDate: '2025-03-11',
                        requestedDate: '2025-04-07',
                        clientPO: '126568/i16/40x4',
                        lotId: 23156,
                        productNumber: 'PF5017RPi16',
                        description: 'Reverse Print - Bleu Reflex',
                        quantity: 34844
                    },
                    {
                        orderCode: 3279,
                        customerCode: 5818,
                        customerName: 'Dominion & Grimm',
                        orderDate: '2025-03-12',
                        requestedDate: '2025-04-08',
                        clientPO: 'OC Tzinia #10',
                        lotId: 23165,
                        productNumber: 'PF5818SPK7413',
                        description: 'Sac en papier kraft 7x4x13"',
                        quantity: 12000
                    }
                ]
            });

            $('#ordersGrid').jqxGrid({
                width: '100%',
                source: ordersData,
                columnsresize: true,
                pageable: true,
                autoheight: true,
                sortable: true,
                columns: [
                    { text: 'Order Code', datafield: 'orderCode', width: 90 },
                    { text: 'Customer Code', datafield: 'customerCode', width: 100 },
                    { text: 'Customer Name', datafield: 'customerName', width: 200 },
                    { text: 'Order Date', datafield: 'orderDate', width: 100, cellsformat: 'yyyy-MM-dd' },
                    { text: 'Requested Date', datafield: 'requestedDate', width: 100, cellsformat: 'yyyy-MM-dd' },
                    { text: 'Client PO', datafield: 'clientPO', width: 120 },
                    { text: 'Lot ID', datafield: 'lotId', width: 80 },
                    { text: 'Product Number', datafield: 'productNumber', width: 120 },
                    { text: 'Product Description', datafield: 'description', width: 250 },
                    { text: 'Quantity', datafield: 'quantity', width: 80 }
                ]
            });

            $('#ordersGrid').on('rowdoubleclick', function () {
                ordersGrid.hide();
                workOrderView.show();
                backButton.show();
            });

            $('#backButton').on('click', function () {
                workOrderView.hide();
                ordersGrid.show();
                backButton.hide();
            });

            // Ink Grid
            const inkDataAdapter = new $.jqx.dataAdapter({
                dataType: "array",
                dataFields: [
                    { name: "station", type: "number" },
                    { name: "inkCode", type: "string" },
                    { name: "type", type: "string" },
                    { name: "color", type: "string" },
                    { name: "pantone", type: "string" },
                    { name: "qtyUsed", type: "number" },
                    { name: "qtyReturned", type: "number" },
                    { name: "stockQty", type: "number" },
                    { name: "notes", type: "string" }
                ],
                localData: Array.from({ length: 8 }, (_, i) => ({
                    station: i + 1,
                    inkCode: "",
                    type: i % 2 === 0 ? "PUR" : "REDUIT/MIXTE",
                    color: "",
                    pantone: "",
                    qtyUsed: 0,
                    qtyReturned: 0,
                    stockQty: 0,
                    notes: ""
                }))
            });

            $("#inkGrid").jqxGrid({
                width: "100%",
                autoheight: true,
                editable: true,
                source: inkDataAdapter,
                columnsresize: true,
                columns: [
                    { text: "Station", datafield: "station", width: 70, editable: false },
                    { text: "Ink Code", datafield: "inkCode", width: 100 },
                    { text: "Type", datafield: "type", width: 120 },
                    { text: "Color", datafield: "color", width: 100 },
                    { text: "Pantone", datafield: "pantone", width: 100 },
                    { text: "Used (kg)", datafield: "qtyUsed", width: 100, cellsformat: "f2" },
                    { text: "Returned (kg)", datafield: "qtyReturned", width: 120, cellsformat: "f2" },
                    { text: "Stock (kg)", datafield: "stockQty", width: 100, cellsformat: "f2" },
                    { text: "Notes", datafield: "notes" }
                ]
            });

            // Printing Process Grid
            const printDataAdapter = new $.jqx.dataAdapter({
                dataType: "array",
                dataFields: [
                    { name: "station", type: "number" },
                    { name: "process", type: "string" },
                    { name: "startTime", type: "date" },
                    { name: "endTime", type: "date" },
                    { name: "duration", type: "number" },
                    { name: "notes", type: "string" }
                ],
                localData: Array.from({ length: 8 }, (_, i) => ({
                    station: i + 1,
                    process: "",
                    startTime: new Date(),
                    endTime: new Date(),
                    duration: 0,
                    notes: ""
                }))
            });

            $("#printGrid").jqxGrid({
                width: "100%",
                autoheight: true,
                editable: true,
                source: printDataAdapter,
                columnsresize: true,
                columns: [
                    { text: "Station", datafield: "station", width: 70, editable: false },
                    { text: "Process", datafield: "process", width: 150 },
                    { text: "Start Time", datafield: "startTime", width: 150, cellsformat: 'yyyy-MM-dd HH:mm:ss' },
                    { text: "End Time", datafield: "endTime", width: 150, cellsformat: 'yyyy-MM-dd HH:mm:ss' },
                    { text: "Duration (min)", datafield: "duration", width: 120, cellsformat: "f2" },
                    { text: "Notes", datafield: "notes" }
                ]
            });
        });
    </script>
@endpush
