@extends('layouts.app')

@section('title', 'Orca Packaging)')

@section('content')
    <div class="pagetitle">
        <h1>Credit Check</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Credit Check</li>
            </ol>
        </nav>
    </div>

            <!-- Grid Section -->
    <div id="followUpGridSection">
        <!-- Alerta de selección -->
        <div id="messageBox" style="display: none; color: red; font-weight: bold; margin-top: 10px;"></div>
        <!-- Toolbar -->
        <div class="d-flex flex-column mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- Título del listado -->
                <h4 class="mb-0 ms-3">List of orders</h4>
                <!-- Filtros -->
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <!--div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmitted" id="is_transmitted" data-field="Commande_Transmit_First" value="transmitted">
                        <label class="form-check-label" for="is_transmitted">Transmit</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmitted" id="is_transmitted" data-field="Transmit" value="transmitted" checked>
                        <label class="form-check-label" for="is_transmitted">Transmit</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_complete" id="is_complete" data-field="Credit_Autorise" value="completed" checked>
                        <label class="form-check-label" for="is_complete">Credit Autorise</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_canceled" id="is_canceled" data-field="isReady_Production" value="cancelled" checked>
                        <label class="form-check-label" for="is_canceled">Ready to produce</label>
                    </div-->
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_blocked" id="is_blocked" data-field="IsCompletedLogic" value="barred">
                        <label class="form-check-label" for="is_blocked">Complet</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_blocked" id="is_blocked" data-field="IsCanceledLogic" value="barred">
                        <label class="form-check-label" for="is_blocked">Cancel</label>
                    </div>
                </div>
            <!-- Botones -->
                <x-permission-users :allowed-roles="['Thomas Admin']">
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" id="btnNew" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Nouvelle commande (New Order)">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button type="button" id="btnEdit" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Modifier commande (Edit Order)">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" id="btnDuplicate" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Dupliquer commande (Duplicate)">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Actualiser la liste (Refresh)">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button type="button" id="btnFollowUps" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Consulter les suivis (Check Follow-Ups)">
                            <i class="bi bi-search"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Etiquetas" onclick="openLabelsModal()">
                            <i class="bi bi-tag"></i>
                        </button>
                    </div>
                </x-permission-users>                
            </div>
        </div>
        <!-- Grid principal -->
        <div id="commandesGrid"></div>
        <div id="recCommandesGrid"></div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/jqwidgets/jqxlistbox.js"></script>
    <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
    <script src="/assets/jqwidgets/jqxcombobox.js"></script>
<script>

$(document).ready(function () {
    const isAdmin = @json(Auth::user()?->fonction?->Fonction_Desc === 'Thomas Admin');


    // Fuente de datos para el grid principal
    var source = {
        datatype: "json",
        datafields: [
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
            { name: 'Lots_Qty', type: 'int' },
            { name: 'Unit_Qty', type: 'string' },
            { name: 'Lots_Price', type: 'float' },
            { name: 'Unit_Price', type: 'string' },
            { name: 'Shipping_Qty', type: 'int' },
            { name: 'Commentaire', type: 'string' },
            { name: 'Lots_Complet', type: 'string' },
            { name: 'Transmit', type: 'boolean' },
            { name: 'Commande_Transmit_First', type: 'boolean' },
            { name: 'Credit_Autorise', type: 'boolean' },
            { name: 'isReady_Production', type: 'boolean' },
            { name: 'IsCompletedLogic', type: 'boolean' },
            { name: 'IsCanceledLogic', type: 'boolean' }
        ],
        url: "{{ url('/production/production/get-commandes') }}",
        id: 'Commande_Id'
    };

    var dataAdapter = new $.jqx.dataAdapter(source);

    // Configuración del grid principal
    $("#commandesGrid").jqxGrid({
        width: '100%',

        source: dataAdapter,
        pagermode: "simple",
            pageable: true,
            autoheight: true,
            sortable: true,
            filterable: true,
            columnsresize: true,
            showfilterrow: true,

     
     showtoolbar: true,
        keyboardnavigation: false,
        pageSize: 10,
        editable: true,
     
        columns: [
            { text: 'Order ID', datafield: 'Commande_Id', width: '4%', align: 'center', cellsalign: 'center', editable: false, hidden: true },
            { text: 'Order Code', datafield: 'InInvoiceNumber', width: '6%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Customer Code', datafield: 'Customer_Code', width: '7%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Customer Name', datafield: 'Customer_Name', width: '20%', align: 'center', editable: false },
            /*{ text: 'Lot ID', datafield: 'Lot_Id', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Product Number', datafield: 'PrNumber', width: '15%', align: 'center', editable: false },
            { text: 'Product Description', datafield: 'PrDescription1', width: '40%', align: 'center', editable: false },
            { text: 'Quantity', datafield: 'Lots_Qty', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Unit', datafield: 'Unit_Qty', width: '4%', align: 'center', cellsalign: 'center', editable: false },*/
            { text: "Commande_Transmit_First", datafield: "Commande_Transmit_First", width: '5%', hidden: true },
            { text: "Transmit", datafield: "Transmit", width: '5%', hidden: true },
            { text: "Credit Autorise", datafield: "Credit_Autorise", width: '5%',hidden: true },
            { text: "isReady Production", datafield: "isReady_Production",width: '5%', hidden: true },
            { text: "Complet", datafield: "IsCompletedLogic",width: '5%', hidden: true },
            { text: "Cancel", datafield: "IsCanceledLogic", width: '5%',hidden: true }
        ],
        ready: function () {
            // Filtros por defecto al cargar
            const filters = [
                { field: "Commande_Transmit_First", value: true },
                { field: "Transmit", value: true },
                { field: "Credit_Autorise", value: false },
                { field: "isReady_Production", value: false },
                { field: "IsCompletedLogic", value: false },
                { field: "IsCanceledLogic", value: false }
            ];
            filters.forEach(filter => {
                let filterGroup = new $.jqx.filter();
                let condition = filterGroup.createfilter('booleanfilter', filter.value, 'equal');
                filterGroup.addfilter(1, condition);
                $("#commandesGrid").jqxGrid('addfilter', filter.field, filterGroup);
            });
            $("#commandesGrid").jqxGrid('applyfilters');
        }
    });

    // Seleccionar la primera fila por defecto
    $("#commandesGrid").jqxGrid('selectrow', 0);

    // Aplicar filtros dinámicos desde checkboxes
    $(".status-filter").on("change", function () {
        const field = $(this).data("field");
        const isChecked = $(this).is(":checked");

        $("#commandesGrid").jqxGrid('removefilter', field);

        let filterGroup = new $.jqx.filter();
        let filter = filterGroup.createfilter('booleanfilter', isChecked, 'equal');
        filterGroup.addfilter(1, filter);

        $("#commandesGrid").jqxGrid('addfilter', field, filterGroup);
        $("#commandesGrid").jqxGrid('applyfilters');
    });

    // Cuando se selecciona una fila en el grid principal
    $("#commandesGrid").on('rowdoubleclick', function (event) {
        var rowData = event.args.row;

        if (!rowData || !rowData.bounddata.Commande_Id) {
            alert('No hay datos');
            return;
        }

    });


    
});
</script>
@endpush
