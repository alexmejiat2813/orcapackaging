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
                dataType: 'json',
                dataFields: [
                    { name: 'Scheduled_Date', type: 'date' },
                { name: 'Commande_Id', type: 'int' },
                { name: 'Customer_Code', type: 'string' },
                { name: 'Customer_Name', type: 'string' },
                { name: 'InInvoiceNumber', type: 'string' },
                { name: 'Date_Commande', type: 'date' },
                { name: 'Date_Demander', type: 'date' },
                { name: 'Date_Expedition', type: 'date' },
                { name: 'Po_Client', type: 'string' },
                { name: 'Acheteur', type: 'string' },
                { name: 'Lot_Id', type: 'int' },
                { name: 'Product_Id', type: 'int' },
                { name: 'PrNumber', type: 'string' },
                { name: 'PrDescription1', type: 'string' },
                { name: 'Lots_Qty', type: 'float' },
                { name: 'Qty_InStock', type: 'float' },
                { name: 'Lots_Price', type: 'float' },
                { name: 'Shipping_Qty', type: 'float' },
                { name: 'Commentaire', type: 'string' },
                { name: 'Unit_Qty', type: 'string' },
                { name: 'Unit_Price', type: 'string' },
                { name: 'SubTotal', type: 'float' },
                { name: 'Total', type: 'float' },
                { name: 'Qty_Finish', type: 'float' },
                { name: 'Transmit', type: 'boolean' },
                { name: 'Credit_Autorise', type: 'boolean' },
                { name: 'isReady_Production', type: 'boolean' },
                { name: 'IsCompletedLogic', type: 'boolean' },
                { name: 'IsCanceledLogic', type: 'boolean' },
                { name: 'Commande_Transmit_First', type: 'boolean' },
                ], id: 'Commande_Id',
                url: '/production/orders/today-schedule?equipment_id=17'
                
            });

            $('#ordersGrid').jqxGrid({
                width: '100%',
                source: ordersData,
                columnsresize: true,
                pageable: true,
                autoheight: true,
                sortable: true,
                columns: [
                    { text: 'Order Code', datafield: 'InInvoiceNumber', width: 90 },
                    { text: 'Customer Code', datafield: 'Customer_Code', width: 100 },
                    { text: 'Customer Name', datafield: 'Customer_Name', width: 200 },
                    { text: 'Order Date', datafield: 'Date_Commande', width: 100, cellsformat: 'yyyy-MM-dd' },
                    { text: 'Requested Date', datafield: 'Date_Demander', width: 100, cellsformat: 'yyyy-MM-dd' },
                    { text: 'Client PO', datafield: 'Po_Client', width: 120 },
                    { text: 'Lot ID', datafield: 'Lot_Id', width: 80 },
                    { text: 'Product Number', datafield: 'PrNumber', width: 120 },
                    { text: 'Product Description', datafield: 'PrDescription1', width: 250 },
                    { text: 'Quantity', datafield: 'Lots_Qty', width: 80 }
                ],
                
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
