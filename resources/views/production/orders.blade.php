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

    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
        <!-- Button visible only for admin -->
    @endif

    <section class="section mt-4">
        <div id="commandesGrid"></div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const isAdmin = @json(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif');

        // Define source for jqxGrid
        var source = {
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
                { name: 'Qty_Finish', type: 'float' }
            ],
            url: "{{ url('/production/production/get-commandes') }}",
            id: 'Commande_Id'
        };

        var dataAdapter = new $.jqx.dataAdapter(source);

        // jqxGrid configuration
        $("#commandesGrid").jqxGrid({
            width: '100%',
            source: dataAdapter,
            pageable: true,
            autoheight: true,
            sortable: true,
            filterable: true,
            columnsresize: true,
            showfilterrow: true,
            pageSize: 17,
            editable: true,
            showtoolbar: true,
            rendertoolbar: function (toolbar) {
                var container = $("<div style='margin: 5px;'></div>");
                toolbar.append(container);
                container.append('<input class="btn btn-primary" id="syncButton" type="button" value="Synchronize Schedule" />');
                $("#syncButton").jqxButton();

                $('#syncButton').on('click', function () {
                    const rows = $('#commandesGrid').jqxGrid('getrows');

                    // Only rows with a valid Scheduled_Date will be processed
                    const selectedLots = rows
                        .filter(row => row.Scheduled_Date)
                        .map(row => ({
                            lot_id: row.Lot_Id,
                            commande_id: row.Commande_Id,
                            Scheduled_Date: row.Scheduled_Date
                        }));

                    if (selectedLots.length === 0) {
                        $('#commandesGrid').jqxGrid('updatebounddata');
                        return;
                    }

                    fetch("{{ url('/production/orders/sync-schedule') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ lots: selectedLots })
                    })
                    .then(response => response.json())
                    .then(result => {
                        alert('Synchronization complete. Changes made: ' + result.updated);
                        $('#commandesGrid').jqxGrid('updatebounddata');
                    })
                    .catch(error => {
                        console.error('Error syncing:', error);
                        alert('An error occurred during synchronization.');
                    });
                });
            },
            columns: [
                { text: 'Scheduled Date', datafield: 'Scheduled_Date', width: 110, columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: isAdmin },
                { text: 'ID', datafield: 'Commande_Id', width: 100 },
                { text: '# Customer', datafield: 'Customer_Code', width: 95, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Customer', datafield: 'Customer_Name', width: 255, align: 'center', editable: false },
                { text: '# Order', datafield: 'InInvoiceNumber', width: 75, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Client PO', datafield: 'Po_Client', width: 160, align: 'center', editable: false },
                { text: 'Order Date', datafield: 'Date_Commande', width: 110, cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', align: 'center', cellsalign: 'center', editable: false },
                { text: 'Requested Date', datafield: 'Date_Demander', width: 110, cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', align: 'center', cellsalign: 'center', editable: false },
                { text: 'Lot ID', datafield: 'Lot_Id', width: 60, align: 'center', cellsalign: 'center', editable: false },
                { text: '# Product', datafield: 'PrNumber', width: 180, align: 'center', editable: false },
                { text: 'Product', datafield: 'PrDescription1', width: 500, align: 'center', editable: false },
                { text: 'Order Quantity', datafield: 'Lots_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Shipping Quantity', datafield: 'Shipping_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Finish Quantity', datafield: 'Qty_Finish', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Sub-Total', datafield: 'SubTotal', width: 100, cellsformat: 'c2', align: 'center', cellsalign: 'right', editable: false },
                { text: 'Total', datafield: 'Total', width: 100, cellsformat: 'c2', align: 'center', cellsalign: 'right', editable: false }
            ]
        });
    });
</script>
@endpush
