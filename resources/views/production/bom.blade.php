@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>BOM (Bill of Materials)</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">BOM (Bill of Materials)</li>
            </ol>
        </nav>
    </div>

    <section class="section mt-4">
        <div id="commandesGrid"></div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    const isAdmin = @json(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif');

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
        url: "{{ url('/production/bom/get-commandes') }}",
        id: 'Commande_Id'
    };

    var dataAdapter = new $.jqx.dataAdapter(source);

    $("#commandesGrid").jqxGrid({
        width: '100%',
        source: dataAdapter,
        pageable: true,
        autoheight: true,
        sortable: true,
        filterable: true,
        columnsresize: true,
        pageSize: 18,
        editable: true,
        rowdetails: true,
        initrowdetails: function (index, parentElement, gridElement, datarecord) {
            const details = $('<div style="margin:10px;"></div>');
            $(parentElement).append(details);
            details.html(`<strong>BOM for Lot ID ${datarecord.Lot_Id}</strong><br/>Loading...`);
            $.get("/production/bom/get-details/" + datarecord.Lot_Id, function (res) {
                let html = '<table class="table table-sm"><thead><tr><th>Code</th><th>Est</th><th>Cons</th><th>PO</th><th>Stock</th><th>Unit</th></tr></thead><tbody>';
                res.forEach(row => {
                    html += `<tr>
                        <td>${row.Material_Code}</td>
                        <td>${row.Estimated_Quantity}</td>
                        <td>${row.Consumed_Quantity}</td>
                        <td>${row.Order_Quantity}</td>
                        <td>${row.Stock_Initial}</td>
                        <td>${row.PrLongUnitCode}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                details.html(html);
            });
        },
        rowdetailstemplate: {
            rowdetails: "<div style='margin: 10px;'></div>",
            rowdetailsheight: 120
        },
        columns: [
            { text: '', datafield: 'Checked', columntype: 'checkbox', width: 40, editable: false },
            { text: 'Order Code', datafield: 'InInvoiceNumber', width: 100, align: 'center', cellsalign: 'center', editable: false },
            { text: 'Customer Code', datafield: 'Customer_Code', width: 110, align: 'center', cellsalign: 'center', editable: false },
            { text: 'Customer Name', datafield: 'Customer_Name', width: 250, align: 'center', editable: false },
            //{ text: 'Order Date', datafield: 'Date_Commande', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: false },
            //{ text: 'Requested Date', datafield: 'Date_Demander', width: 110, cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: false },
            //{ text: 'Client PO', datafield: 'Po_Client', width: 150, align: 'center', cellsalign: 'center', editable: false },
            { text: 'Lot ID', datafield: 'Lot_Id', width: 60, align: 'center', cellsalign: 'center', editable: false },
            { text: 'Product Number', datafield: 'PrNumber', width: 180, align: 'center', cellsalign: 'center', editable: false },
            { text: 'Product Description', datafield: 'PrDescription1', width: 680, align: 'center', editable: false },
            { text: 'Quantity', datafield: 'Lots_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
        ]
    });
});
</script>
@endpush
