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

            <!-- Grid Section -->
    <div id="followUpGridSection">
        <!-- Alerta de selección -->
        <div id="messageBox" style="display: none; color: red; font-weight: bold; margin-top: 10px;"></div>
        <!-- Toolbar -->
        <div class="d-flex flex-column mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <!-- Botones -->
                <x-permission-users :allowed-roles="['Thomas Admin']">
                    <div class="d-flex gap-2">
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
                    </div>
                </x-permission-users>
                <!-- Título del listado -->
                <h4 class="mb-0 ms-3">List of orders</h4>
                <!-- Filtros -->
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmitted" id="is_transmitted" data-field="Transmit" value="transmitted" checked>
                        <label class="form-check-label" for="is_transmitted">Transmit</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_complete" id="is_complete" data-field="Credit_Autorise" value="completed" checked>
                        <label class="form-check-label" for="is_complete">Credit Autorise</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_canceled" id="is_canceled" data-field="isReady_Production" value="cancelled">
                        <label class="form-check-label" for="is_canceled">Ready to produce</label>
                    </div>
                    <!--div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_blocked" id="is_blocked" data-field="IsCompletedLogic" value="barred">
                        <label class="form-check-label" for="is_blocked">Complet</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_blocked" id="is_blocked" data-field="IsCancelledLogic" value="barred">
                        <label class="form-check-label" for="is_blocked">Cancel</label>
                    </div-->
                </div>
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

    const products = @json($products->map(fn($p) => [
        'label' => $p->PrNumber . ': ' . $p->PrDescription1 . ' ' . $p->PrDescription2 . ' ' . $p->PrDescription3,
        'PrNumber' => $p->PrNumber,
        'value' => $p->Product_ID
    ]));

    var productsSource = {
        datatype: "array",
        datafields: [
            { name: 'label', type: 'string' },
            { name: 'PrNumber', type: 'string' },
            { name: 'value', type: 'number' }
        ],
        localdata: products
    };

    var productsAdapter = new $.jqx.dataAdapter(productsSource, { autoBind: true });

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
            { name: 'Credit_Autorise', type: 'boolean' },
            { name: 'isReady_Production', type: 'boolean' },
            { name: 'IsCompletedLogic', type: 'boolean' },
            { name: 'IsCancelledLogic', type: 'boolean' }
        ],
        url: "{{ url('/production/production/get-commandes') }}",
        id: 'Commande_Id'
    };

    var dataAdapter = new $.jqx.dataAdapter(source);

    // Configuración del grid principal
    $("#commandesGrid").jqxGrid({
        width: '100%',

        source: dataAdapter,
        pageable: true,
        autoheight: true,
        sortable: true,
        filterable: true,
        columnsresize: true,
        keyboardnavigation: false,
        pageSize: 10,
        editable: true,
     
        columns: [
            { text: 'Order ID', datafield: 'Commande_Id', width: '4%', align: 'center', cellsalign: 'center', editable: false, hidden: true },
            { text: 'Order Code', datafield: 'InInvoiceNumber', width: '6%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Customer Code', datafield: 'Customer_Code', width: '7%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Customer Name', datafield: 'Customer_Name', width: '18%', align: 'center', editable: false },
            { text: 'Lot ID', datafield: 'Lot_Id', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Product Number', datafield: 'PrNumber', width: '15%', align: 'center', editable: false },
            { text: 'Product Description', datafield: 'PrDescription1', width: '40%', align: 'center', editable: false },
            { text: 'Quantity', datafield: 'Lots_Qty', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Unit', datafield: 'Unit_Qty', width: '4%', align: 'center', cellsalign: 'center', editable: false },
            { text: "Transmit", datafield: "Transmit", hidden: true },
            { text: "Credit Autorise", datafield: "Credit_Autorise", hidden: true },
            { text: "isReady Production", datafield: "isReady_Production", hidden: true },
            { text: "Complet", datafield: "IsCompletedLogic", hidden: true },
            { text: "Cancel", datafield: "IsCancelledLogic", hidden: true }
        ],
        ready: function () {
            // Filtros por defecto al cargar
            const filters = [
                { field: "Transmit", value: true },
                { field: "Credit_Autorise", value: true },
                { field: "isReady_Production", value: false },
                { field: "IsCompletedLogic", value: false },
                { field: "IsCancelledLogic", value: false }
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

        var dataSource = {
            datatype: "json",
            datafields: [
                { name: 'Commande_Id_CR', type: 'int' },
                { name: 'Commande_Receipe_Id', type: 'int' },
                { name: 'Quotation_Receipe_Id', type: 'int' },
                { name: 'Quotation_Receipe_Departement_Id', type: 'int' },
                { name: 'Department_Description', type: 'string' },
                { name: 'PrNumber', type: 'string' },
                {
                    name: 'ProductDisplay',
                    value: 'Product_ID',
                    values: {
                        source: productsAdapter.records,
                        value: 'value',
                        name: 'label'
                    }
                },
                { name: 'Value', type: 'float' },
                { name: 'Stock_Qty', type: 'int' },
                { name: 'Unit_Measurement_Description', type: 'string' },
                { name: 'PO_No', type: 'string' },
                { name: 'Order_Quantity', type: 'int' },
                { name: 'Unit_Qty', type: 'string' },
                { name: 'Actif', type: 'boolean' },
            ],
            id: 'MaterialCheck_ID',
            url: '/production/bom/get-details/' + rowData.bounddata.Commande_Id
        };

        var adapter = new $.jqx.dataAdapter(dataSource);
        $("#recCommandesGrid").jqxGrid({ source: adapter });
    });

    // Configurar segundo grid (detalle BOM)
    $("#recCommandesGrid").jqxGrid({
        width: '100%',
        autoheight: true,
        keyboardnavigation: false,
        editable: true,
        columns: [
            { text: 'Commande_Id_CR', datafield: 'Commande_Id_CR', width: '5%', align: 'center', editable: false, hidden: true },
            { text: 'Commande_Receipe_Id', datafield: 'Commande_Receipe_Id', width: '5%', align: 'center', editable: false, hidden: true },
            { text: 'Quotation_Receipe_Id', datafield: 'Quotation_Receipe_Id', width: '5%', align: 'center', editable: false, hidden: true },
            { text: 'Actif', datafield: 'Actif', width: '3%', columntype: 'checkbox', align: 'center', cellsalign: 'center', editable: isAdmin },
            { text: 'Department', datafield: 'Department_Description', width: '7%', align: 'center', cellsalign: 'center', editable: false },
            
            {
                text: 'Produit',
                datafield: 'Product_ID',
                displayfield: 'ProductDisplay',
                columntype: 'combobox', align: 'center',
                width: '45%', editable: isAdmin,
                createeditor: function (row, value, editor) {
                    editor.jqxComboBox({
                        source: productsAdapter,
                        displayMember: 'label',
                        valueMember: 'value',
                        autoDropDownHeight: true,
                        searchMode: 'containsignorecase',
                        autoComplete: false,
                        remoteAutoComplete: false,
                        keyboardSelection: true
                    });

                    // 🔍 Escucha el evento de teclado
                    editor.on('keyup', function (event) {
                        const inputValue = editor.val();
                        editor.searchString = inputValue;
                        editor._searchString = inputValue; // for older versions
                    });
                },

            },
            { text: 'Estime Qty', datafield: 'Value', width: '5%', align: 'center', cellsalign: 'center', editable: isAdmin },
            { text: 'PO No', datafield: 'PO_No', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'PO Qty', datafield: 'Order_Quantity', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Unit PO', datafield: 'Unit_Qty', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Stock Qty', datafield: 'Stock_Qty', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Unit Stock', datafield: 'Unit_Measurement_Description', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: '', datafield: 'Save', columntype: 'button',  width: '5%',
                cellsrenderer: function () { return "Save"; },
                buttonclick: function (row) {
                    event.preventDefault();
                    var dataRecord = $("#recCommandesGrid").jqxGrid('getrowdata', row);

                    $.ajax({
                        url: '/production/bom/save-recipe',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            Commande_Id_CR: dataRecord.Commande_Id_CR,
                            Product_Id: dataRecord.Product_ID,
                            Quotation_Receipe_Id: dataRecord.Quotation_Receipe_Id,
                            Value: dataRecord.Value,
                            Actif: dataRecord.Actif ? 1 : 0
                        },
                        success: function (response) {
                            alert(response.message);
                            $("#recCommandesGrid").jqxGrid('updatebounddata');
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert('An error occurred while saving.');
                        }
                    });

                 }
            },
            { text: '', datafield: 'Delete', columntype: 'button',  width: '5%',
                cellsrenderer: function () { return "Delete"; },
                buttonclick: function (row) {
                    var dataRecord = $("#recCommandesGrid").jqxGrid('getrowdata', row);
                    var commandeReceipeId = dataRecord.Commande_Receipe_Id;
                    var quotationReceipeId = dataRecord.Quotation_Receipe_Id;

                    // Confirmación con el usuario
                    if (!confirm("Are you sure you want to delete this record?")) {
                        return;
                    }

                    // Enviar petición AJAX
                    $.ajax({
                        url: "/production/bom/delete-recipe", // tu ruta Laravel
                        type: "POST",
                        data: {
                            Commande_Receipe_Id: commandeReceipeId,
                            Quotation_Receipe_Id: quotationReceipeId,
                            _token: $('meta[name="csrf-token"]').attr('content') // asegúrate de tener el token en el header
                        },
                        success: function (response) {
                            if (response.success) {
                                // Recargar el grid de detalles
                                $("#recCommandesGrid").jqxGrid('deleterow', row);
                                $("#recCommandesGrid").jqxGrid('updatebounddata');
                                // O si prefieres refrescar todo desde backend:
                                // $("#recCommandesGrid").jqxGrid('updatebounddata');
                            } else {
                                alert("Error while deleting.");
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert("An error occurred.");
                        }
                    });
                }
            },
            { text: '', datafield: 'Purchase', columntype: 'button',  width: '5%',
                cellsrenderer: function () { return "Purchase"; },
                buttonclick: function (row) {
                     // open the popup window when the user clicks a button.
                     editrow = row;
                     //var offset = $("#recCommandesGrid").offset();
                    
                     var dataRecord = $("#recCommandesGrid").jqxGrid('getrowdata', editrow);
                     alert(dataRecord.Product_ID);
                 }
            },
        ]
    });

    
});
</script>
@endpush
