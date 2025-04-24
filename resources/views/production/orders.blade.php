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
        <div id="commandesGrid"></div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Definir el origen de los datos para jqxGrid
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
                pageSize: 18,
                columns: [
                    //{ text: 'Scheduled Date', datafield: 'Scheduled_Date', width: 150, cellsformat: 'yyyy-MM-dd' },
                    //{ text: 'Commande ID', datafield: 'Commande_Id', width: 100 },
                    { text: 'Order Code', datafield: 'InInvoiceNumber', width: 100, align: 'center', cellsalign: 'center' },
                    { text: 'Customer Code', datafield: 'Customer_Code', width: 110, align: 'center', cellsalign: 'center' },
                    { text: 'Customer Name', datafield: 'Customer_Name', width: 250, align: 'center' },
                    
                    { text: 'Order Date', datafield: 'Date_Commande', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center' },
                    { text: 'Requested Date', datafield: 'Date_Demander', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center' },
                    //{ text: 'Expedition Date', datafield: 'Date_Expedition', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center' },
                    { text: 'Client PO', datafield: 'Po_Client', width: 150, align: 'center', cellsalign: 'center' },
                    //{ text: 'Buyer', datafield: 'Acheteur', width: 150 },
                    //{ text: 'Transmit', datafield: 'Transmit', width: 80, align: 'center', cellsalign: 'center' },
                    //{ text: 'Ready for Production', datafield: 'isReady_Production', width: 150, align: 'center', cellsalign: 'center' },
                    { text: 'Lot ID', datafield: 'Lot_Id', width: 60, align: 'center', cellsalign: 'center' },
                    //{ text: 'Product ID', datafield: 'Product_Id', width: 80, align: 'center', cellsalign: 'center' },
                    { text: 'Product Number', datafield: 'PrNumber', width: 180, align: 'center', cellsalign: 'center' },
                    { text: 'Product Description', datafield: 'PrDescription1', width: 680, align: 'center' },
                    { text: 'Quantity', datafield: 'Lots_Qty', width: 100, align: 'center', cellsalign: 'center' },
                    //{ text: 'Price', datafield: 'Lots_Price', width: 100 },
                    //{ text: 'Shipping Quantity', datafield: 'Shipping_Qty', width: 120 },
                    //{ text: 'Comment', datafield: 'Commentaire', width: 200 },
                    //{ text: 'Lot Completion', datafield: 'Lots_Complet', width: 150 },
                ]
            });
        });
    </script>
@endpush
