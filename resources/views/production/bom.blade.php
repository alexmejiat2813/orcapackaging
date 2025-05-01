@extends('layouts.app')

@section('title', 'BOM (Bill of Materials)')

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
            { name: 'Schedule_ID', type: 'int' },
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
        url: "{{ url('/production/production/get-commandes') }}",
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
            const container = $('<div style="margin:10px;"></div>');
            $(parentElement).append(container);

            const detailSource = {
                datatype: "json",
                datafields: [
                    { name: 'MaterialCheck_ID', type: 'int' },
                    { name: 'Schedule_ID', type: 'int' },
                    { name: 'Material_Code', type: 'string' },
                    { name: 'Estimated_Quantity', type: 'float' },
                    { name: 'Consumed_Quantity', type: 'float' },
                    { name: 'Order_Quantity', type: 'float' },
                    { name: 'Stock_Initial', type: 'float' },
                    { name: 'PrLongUnitCode', type: 'string' },
                    { name: 'Material_Quantity', type: 'float' },
                    { name: 'Consumo_Acumulado', type: 'float' },
                    { name: 'Stock_Remaining', type: 'float' },
                    { name: 'Checked', type: 'bool' },
                    { name: 'Comment', type: 'string' },
                ],
                id: 'MaterialCheck_ID',
                url: '/production/bom/get-details/' + datarecord.Lot_Id,
                addrow: function (rowid, rowdata, position, commit) {
                    $.ajax({
                        type: "POST",
                        url: "/production/bom/detail/store",
                        data: JSON.stringify({
                            Schedule_ID: datarecord.Lot_Id,
                            Material_Code: rowdata.Material_Code,
                            Material_Quantity: rowdata.Material_Quantity,
                            Unit_Measurement_ID: rowdata.Unit_Measurement_ID
                        }),
                        contentType: 'application/json',
                        success: function () {
                            commit(true);
                        },
                        error: function () {
                            commit(false);
                        }
                    });
                },
                updaterow: function (rowid, rowdata, commit) {
                    $.ajax({
                        type: "PUT",
                        url: "/production/bom/detail/update/" + rowdata.MaterialCheck_ID,
                        data: JSON.stringify(rowdata),
                        contentType: 'application/json',
                        success: function () {
                            commit(true);
                        },
                        error: function () {
                            commit(false);
                        }
                    });
                },
                deleterow: function (rowid, commit) {
                    $.ajax({
                        type: "DELETE",
                        url: "/production/bom/detail/delete/" + rowid,
                        success: function () {
                            commit(true);
                        },
                        error: function () {
                            commit(false);
                        }
                    });
                }
            };

            const detailAdapter = new $.jqx.dataAdapter(detailSource);

            container.jqxGrid({
                width: '95%',
                height: 200,
                source: detailAdapter,
                editable: true,
                showtoolbar: isAdmin,
                rendertoolbar: function (toolbar) {
                    if (!isAdmin) return;
                    const container = $('<div></div>');
                    const addButton = $('<input type="button" value="Add" />');
                    const deleteButton = $('<input type="button" value="Delete" />');
                    toolbar.append(container);
                    container.append(addButton).append(deleteButton);

                    addButton.jqxButton();
                    deleteButton.jqxButton();

                    addButton.on('click', function () {
                        const datarow = {
                            Material_Code: '',
                            Material_Quantity: 0,
                            Unit_Measurement_ID: 1
                        };
                        container.jqxGrid('addrow', null, datarow);
                    });

                    deleteButton.on('click', function () {
                        const selectedrowindex = container.jqxGrid('getselectedrowindex');
                        const id = container.jqxGrid('getrowid', selectedrowindex);
                        container.jqxGrid('deleterow', id);
                    });
                },
                columns: [
                    { text: 'Material', datafield: 'Material_Code', width: '20%', align: 'center', cellsalign: 'center', editable: false  },
                    { text: 'Qty Estimated', datafield: 'Estimated_Quantity', align: 'center', cellsalign: 'center', width: '10%', editable: false },                    
                    { text: 'Qty PO', datafield: 'Order_Quantity', width: '10%', align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Stock', datafield: 'Stock_Initial', width: '10%', align: 'center', cellsalign: 'center', editable: false },                   
                    { text: 'Qty Consumed', datafield: 'Consumed_Quantity', width: '10%', align: 'center', cellsalign: 'center', editable: false},
                    { text: 'Accum. Consumption', datafield: 'Consumo_Acumulado', width: '10%', align: 'center', cellsalign: 'center', editable: false },                   
                    { text: 'Stock Remaining', datafield: 'Stock_Remaining', width: '10%', align: 'center', cellsalign: 'center', editable: false},
                    { text: 'Qty Check', datafield: 'Material_Quantity', width: '10%', align: 'center', cellsalign: 'center', editable: isAdmin },
                    { text: 'Unit', datafield: 'PrLongUnitCode', width: '5%', align: 'center', cellsalign: 'center', editable: false }
                ]
            });
        },
        rowdetailstemplate: {
            rowdetails: "<div style='margin: 10px;'></div>",
            rowdetailsheight: 240
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
            { text: 'Quantity', datafield: 'Lots_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false }
        ]
    });
});
</script>
@endpush
