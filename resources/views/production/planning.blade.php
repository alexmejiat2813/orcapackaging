@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')

    <div class="pagetitle">
        <h1>Production Planning</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/production/index">Production</a></li>
                <li class="breadcrumb-item active">Production Planning</li>
            </ol>
        </nav>
    </div>

    <div id="splitter">
        <div class="splitter-panel">
            <div style="border: none;" id="jqxTable"></div>
        </div>
        <div class="splitter-panel">
            <section id="schedulerContainer" class="section" style="flex-grow: hidden;">
                <div id="scheduler"></div>
                <div id="messageBox"></div>
            </section>
        </div>
    </div>    

@endsection

@push('scripts')
    <!-- Include jqx scripts -->
    <script src="/assets/jqwidgets/jqxcore.js"></script>
    <script src="/assets/jqwidgets/jqxbuttons.js"></script>
    <script src="/assets/jqwidgets/jqxscrollbar.js"></script>
    <script src="/assets/jqwidgets/jqxdata.js"></script>
    <script src="/assets/jqwidgets/jqxdate.js"></script>
    <script src="/assets/jqwidgets/jqxscheduler.js"></script>
    <script src="/assets/jqwidgets/jqxscheduler.api.js"></script>
    <script src="/assets/jqwidgets/jqxdatetimeinput.js"></script>
    <script src="/assets/jqwidgets/jqxmenu.js"></script>
    <script src="/assets/jqwidgets/jqxcalendar.js"></script>
    <script src="/assets/jqwidgets/jqxtooltip.js"></script>
    <script src="/assets/jqwidgets/jqxwindow.js"></script>
    <script src="/assets/jqwidgets/jqxcheckbox.js"></script>
    <script src="/assets/jqwidgets/jqxlistbox.js"></script>
    <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
    <script src="/assets/jqwidgets/jqxnumberinput.js"></script>
    <script src="/assets/jqwidgets/jqxradiobutton.js"></script>
    <script src="/assets/jqwidgets/jqxinput.js"></script>
    <script src="/assets/jqwidgets/jqxdatatable.js"></script>
    <script src="/assets/jqwidgets/jqxsplitter.js"></script>

    <!-- SweetAlert for alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global machine data -->
    <script>const machineData = @json($machines);</script>

    <!-- Import JS modules -->
    <script type="module" src="/assets/js/config.js"></script>
    <script type="module" src="/assets/js/scheduler.js"></script>

    <script type="text/javascript">

            $(document).ready(function () {

                $("#splitter").jqxSplitter({ width: '100%', height: 700, panels: [{ size: 398, min: 398 }, {min: 400, size: 400, collapsible: false}] })

                var listSource = {
                    datatype: "json",
                    datafields: [
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
                        { name: 'Transmit', type: 'string' },
                        { name: 'isReady_Production', type: 'boolean' },
                        { name: 'Lot_Id', type: 'int' },
                        { name: 'Product_Id', type: 'int' },
                        { name: 'PrNumber', type: 'string' },
                        { name: 'PrDescription1', type: 'string' },
                        { name: 'Lots_Qty', type: 'int' },
                        { name: 'Lots_Price', type: 'float' },
                        { name: 'Shipping_Qty', type: 'float' },
                        { name: 'Commentaire', type: 'string' },
                        { name: 'Lots_Complet', type: 'string' },
                        { name: 'SubTotal', type: 'float' },
                        { name: 'Total', type: 'float' },
                        { name: 'Qty_Finish', type: 'float' },
                    ],
                    id: 'Commande_Id',
                    url: '/production/production/get-commandes'
                };

                let dataAdapter = new $.jqx.dataAdapter(listSource);

                $("#jqxTable").jqxGrid({
                    
   

            height: 700,
            sortable: true,
            filterable: true,
            //columnsresize: true,
            showfilterrow: true,
            source: dataAdapter,
            keyboardnavigation: false,
            enabletooltips: true,
            contextmenuenabled: true,
            showgroupsheader: true,
                    columns: [
                        { text: 'Customer', dataField: 'Customer_Name', width: '25%', align: 'center' },
                        { text: 'CMD', dataField: 'InInvoiceNumber', width: '8%', align: 'center', cellsalign: 'center' },
                        { text: 'Product', dataField: 'PrDescription1', width: '35%', align: 'center'
                    }]
                });
                $('#jqxTable').on('rowdoubleclick', function (event) {

                    alert("The row you clicked twice is: " + event.args.datafield );
                });
            });
        </script>
@endpush

