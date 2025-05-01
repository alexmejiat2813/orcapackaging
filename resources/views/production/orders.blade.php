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
        <div class="mt-3">
            <button id="syncButton" class="btn btn-primary">⟳ Synchronize Schedule</button>
        </div>
    @endif

    <!-- Tabla para mostrar los datos de Commandes -->
    <section class="section mt-4">
        <div id="commandesGrid"></div>
    </section>

    

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const isAdmin = @json(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif');
            // Definir el origen de los datos para jqxGrid
            var source = {
                datatype: "json",
                datafields: [
                    { name: 'Checked', type: 'bool' },
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
                    { name: 'Shipping_Qty', type: 'int' },
                    { name: 'Commentaire', type: 'string' },
                    { name: 'Lots_Complet', type: 'string' },
                ],
                url: "{{ url('/production/production/get-commandes') }}", // Ruta para obtener los datos
                id: 'Commande_Id'
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            // Configuración de jqxGrid
            $("#commandesGrid").jqxGrid({
                width: '100%',
                source: dataAdapter,
                pageable: true,
                autoheight: true,
                sortable: true,
                filterable: true,
                columnsresize: true,
                showfilterrow: true,
                pageSize: 18,
                editable: true,
                //selectionmode: 'checkbox',
                columns: [
                    { text: '', datafield: 'Checked', columntype: 'checkbox', width: 40, editable: isAdmin },
                    { text: 'Scheduled Date', datafield: 'Scheduled_Date', width: 110, columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: isAdmin },
                    //{ text: 'Commande ID', datafield: 'Commande_Id', width: 100 },
                    { text: 'Order Code', datafield: 'InInvoiceNumber', width: 100, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Customer Code', datafield: 'Customer_Code', width: 110, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Customer Name', datafield: 'Customer_Name', width: 250, align: 'center', editable: false },
                    
                    { text: 'Order Date', datafield: 'Date_Commande', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Requested Date', datafield: 'Date_Demander', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: false },
                    //{ text: 'Expedition Date', datafield: 'Date_Expedition', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center' },
                    { text: 'Client PO', datafield: 'Po_Client', width: 150, align: 'center', cellsalign: 'center', editable: false },
                    //{ text: 'Buyer', datafield: 'Acheteur', width: 150 },
                    //{ text: 'Transmit', datafield: 'Transmit', width: 80, align: 'center', cellsalign: 'center' },
                    //{ text: 'Ready for Production', datafield: 'isReady_Production', width: 150, align: 'center', cellsalign: 'center' },
                    { text: 'Lot ID', datafield: 'Lot_Id', width: 60, align: 'center', cellsalign: 'center', editable: false },
                    //{ text: 'Product ID', datafield: 'Product_Id', width: 80, align: 'center', cellsalign: 'center' },
                    { text: 'Product Number', datafield: 'PrNumber', width: 180, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Product Description', datafield: 'PrDescription1', width: 500, align: 'center', editable: false },
                    { text: 'Quantity', datafield: 'Lots_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
                    //{ text: 'Price', datafield: 'Lots_Price', width: 100 },
                    //{ text: 'Shipping Quantity', datafield: 'Shipping_Qty', width: 120 },
                    //{ text: 'Comment', datafield: 'Commentaire', width: 200 },
                    //{ text: 'Lot Completion', datafield: 'Lots_Complet', width: 150 },
                ]
            });

            $('#syncButton').on('click', function () {
                const rows = $('#commandesGrid').jqxGrid('getrows');
            
                const selectedLots = rows.map(row => ({
                    lot_id: row.Lot_Id,
                    checked: !!row.Checked,
                    current: !!row.Checked, // Default to current as true, may adjust on controller
                    Scheduled_Date: row.Scheduled_Date
                }));

                for (const row of rows) {
                        if (row.Checked && !row.Scheduled_Date) {
                            alert(`Lot ${row.Lot_Id} is checked but has no date assigned.`);
                            return;
                        }
                        if (!row.Checked && row.Scheduled_Date) {
                            alert(`Lot ${row.Lot_Id} has a date but is not checked.`);
                            return;
                        }
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
        });
    </script>
@endpush
