@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
        <h1>Purchasing</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Purchase Orders</li>
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
                        <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Nouvelle commande (New Order)">
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
                <h4 class="mb-0 ms-3">List of unbilled purchase orders</h4>

                <!-- Filtros -->
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmitted" id="is_transmitted" data-field="PO_Transmit" value="transmitted" checked>
                        <label class="form-check-label" for="is_transmitted">Transmis</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_complete" id="is_complete" data-field="PO_Completed" value="completed">
                        <label class="form-check-label" for="is_complete">Complet</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_canceled" id="is_canceled" data-field="PO_Cancel" value="cancelled">
                        <label class="form-check-label" for="is_canceled">Annulé</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_blocked" id="is_blocked" data-field="PO_Lock" value="barred">
                        <label class="form-check-label" for="is_blocked">Barré</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid principal -->
        <div id="gridFollowUp"></div>
    </div>

    <!-- Formulario oculto de seguimiento -->
    <div id="followUpFormSection" class="border rounded p-3 bg-white" style="display: none;">
<div class="row g-4 align-items-stretch">
  <div class="col-md-4 d-flex">
    <div id="form1" class="flex-fill"></div>
  </div>
  <div class="col-md-4 d-flex">
    <div id="form2" class="flex-fill"></div>
  </div>
  <div class="col-md-4 d-flex">
    <div id="form3" class="flex-fill"></div>
  </div>
</div>

<div class="mt-4">
  <label class="form-label fw-bold">Note</label>
  <textarea class="form-control" rows="6" readonly style="white-space: pre-wrap;">CMD CLI 5035
SAC MASTER 50 LB PATATES
J-FILM 35" X 37"
Sac imprimé par Orca
Orca fournie les boîtes et les étiquettes</textarea>
</div>

<div class="mt-4">
  <div id="productDetailGrid"></div>
</div>
    </div>

@endsection

@push('scripts')
<script src="/assets/jqwidgets/jqxgrid.columnsreorder.js"></script>
<script src="/assets/jqwidgets/jqxform.js"></script>
<script src="/assets/jqwidgets/jqxtextarea.js"></script>
<script>
    $(document).ready(function () {

    // Función para obtener la fila seleccionada
    function getSelectedRowData() {
        const selectedRowIndex = $("#gridFollowUp").jqxGrid('getselectedrowindex');
        if (selectedRowIndex === -1) {
            // ⚠️ Alerta si no hay fila seleccionada
            $("#messageBox").html("⚠️ Por favor, seleccione una fila primero.").fadeIn().delay(2000).fadeOut();
            return null;
        }
        return $("#gridFollowUp").jqxGrid('getrowdata', selectedRowIndex);
    }

    // Acción: Editar
    $("#btnEdit").on('click', function () {
        const rowData = getSelectedRowData();
        if (rowData) {
            alert("Editar fila:", rowData);
             $("#followUpGridSection").hide();
            $("#followUpFormSection").show();
            // Aquí va tu lógica de edición
        }
    });

    // Acción: Duplicar
    $("#btnDuplicate").on('click', function () {
        const rowData = getSelectedRowData();
        if (rowData) {
            alert("Duplicar fila:", rowData);
             $("#followUpGridSection").hide();
            $("#followUpFormSection").show();
            // Aquí va tu lógica de duplicación
        }
    });

    // Acción: Buscar
    $("#btnFollowUps").on('click', function () {
        const rowData = getSelectedRowData();
        if (rowData) {
            alert("Buscar con datos:", rowData);
             $("#followUpGridSection").hide();
            $("#followUpFormSection").show();
            // Aquí va tu lógica de búsqueda
        }
    });

        let source = {
            datatype: "json",
            url: "/purchasing/orders/data",
            datafields: [
                { name: "PO_ID", type: "int" },
                { name: "PO_No", type: "string" },
                { name: "PO_Note", type: "string" },
                { name: "Supplier_No", type: "string" },
                { name: "Supplier_Name", type: "string" },
                { name: "PO_Date", type: "date" },
                { name: "PO_Date_Reception", type: "date" },
                { name: "PO_Total", type: "number" },
                { name: "Reception_Status", type: "string" },
                { name: "User_PO", type: "string" },
                { name: "PO_Completed", type: "bool" },
                { name: "PO_Transmit", type: "bool" },
                { name: "PO_Cancel", type: "bool" },
                { name: "PO_Lock", type: "bool" },
            ],
        };

        let dataAdapter = new $.jqx.dataAdapter(source);

        $("#gridFollowUp").jqxGrid({
            width: '100%',
            autoheight: true,
            pageable: true,
            sortable: true,
            filterable: true,
            showfilterrow: true,
            source: dataAdapter,
            columnsresize: true,
            columnsreorder: true,
            pageSize: 17,
            pagermode: "simple",
            columns: [
                { text: "Supplier Code", datafield: "Supplier_No", width: '8%', align: 'center', cellsalign: 'center' },
                { text: "Supplier", datafield: "Supplier_Name", width: '20%', align: 'center' },
                { text: "PO", datafield: "PO_No", width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Date", datafield: "PO_Date", width: '8%', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', filtertype: 'range' },
                { text: "Date_Requis", datafield: "PO_Date_Reception", width: '8%', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', filtertype: 'range' },
                { text: "Note", datafield: "PO_Note", width: '30%', align: 'center' },                
                { text: "Reception Status", datafield: "Reception_Status", width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Total", datafield: "PO_Total", width: '10%', cellsformat: 'c2', align: 'center', cellsalign: 'right' },    
                { text: "Completed", datafield: "PO_Completed", hidden: true },
                { text: "Transmit", datafield: "PO_Transmit", hidden: true },
                { text: "Cancel", datafield: "PO_Cancel", hidden: true },
                { text: "Lock", datafield: "PO_Lock", hidden: true },
            ],
            ready: function () {
                // Aplicar filtros por defecto al cargar el grid
                const filters = [
                    { field: "PO_Transmit", value: true },
                    { field: "PO_Completed", value: false },
                    { field: "PO_Cancel", value: false },
                    { field: "PO_Lock", value: false }
                ];
                filters.forEach(filter => {
                    let filterGroup = new $.jqx.filter();
                    let value = filter.value;
                    let filterCondition = filterGroup.createfilter('booleanfilter', value, 'equal');
                    filterGroup.addfilter(1, filterCondition);
                    $("#gridFollowUp").jqxGrid('addfilter', filter.field, filterGroup);
                });
                $("#gridFollowUp").jqxGrid('applyfilters');
            }
        });


        // Filtros dinámicos al cambiar los checkboxes
        $(".status-filter").on("change", function () {
            const field = $(this).data("field");
            const isChecked = $(this).is(":checked");

            $("#gridFollowUp").jqxGrid('removefilter', field);

            let filterGroup = new $.jqx.filter();
            let value = isChecked ? true : false;
            let filter = filterGroup.createfilter('booleanfilter', value, 'equal');
            filterGroup.addfilter(1, filter);
            $("#gridFollowUp").jqxGrid('addfilter', field, filterGroup);

            $("#gridFollowUp").jqxGrid('applyfilters');
        });











        const formData = {
            supplier_id: '8367B',
            supplier_name: 'Emballages Pro Innovation Inc',
            contact_person: 'Andre Begin',
            address1: '4001 Boul Industriel',
            address2: '(Junise)',
            city: 'Laval',
            postal_code: 'H7L 4S3',
            phone: '1(514)582-1670',
            fax: ''
        };

        $('#form1').jqxForm({
            template: [
                { bind: 'supplier_id', type: 'text', label: '# Fournisseur', labelPosition: 'left', labelWidth: '120px', width: '100%', required: true },
                {
                    bind: 'supplier_name',
                    type: 'option',
                    component: 'jqxDropDownList',
                    label: 'Fournisseur',
                    labelPosition: 'left',
                    labelWidth: '120px',
                    width: '100%',
                    required: true,
                    options: [
                        { label: 'Emballages Pro Innovation Inc', value: 'Emballages Pro Innovation Inc' },
                        { label: 'Autre Fournisseur', value: 'Autre Fournisseur' }
                    ]
                },
                {
                    bind: 'contact_person',
                    type: 'option',
                    component: 'jqxDropDownList',
                    label: 'A/s',
                    labelPosition: 'left',
                    labelWidth: '120px',
                    width: '100%',
                    options: [
                        { label: 'Andre Begin', value: 'Andre Begin' },
                        { label: 'Autre', value: 'Autre' }
                    ]
                },
                { bind: 'address1', type: 'text', label: 'Adresse', labelPosition: 'left', labelWidth: '120px', width: '100%', required: true },
                { bind: 'address2', type: 'text', label: 'Adresse 2', labelPosition: 'left', labelWidth: '120px', width: '100%' },
                { bind: 'city', type: 'text', label: 'Ville', labelPosition: 'left', labelWidth: '120px', width: '100%', required: true },
                { bind: 'postal_code', type: 'text', label: 'Code postal', labelPosition: 'left', labelWidth: '120px', width: '100%', required: true },
                { bind: 'phone', type: 'text', label: 'Tél.', labelPosition: 'left', labelWidth: '120px', width: '100%', required: true },
                { bind: 'fax', type: 'text', label: 'Fax', labelPosition: 'left', labelWidth: '120px', width: '100%' }
            ],
            value: formData
        });


        const formData2 = {
            buyer: 'Mejia Alexander',
            type: 'Achat',
            warehouse: 'Orca Packaging',
            dock: 'Porte de derrière',
            address1: '11810 Lucien-Grendon',
            city: 'Montréal',
            postal_code: 'H1E 7A8',
            phone: '15143605625',
            fax: ''
        };

        $('#form2').jqxForm({
            template: [
                { bind: 'buyer', type: 'text', label: 'Acheteur', labelPosition: 'left', labelWidth: '120px', width: '100%' },
                {
                    bind: 'type',
                    type: 'option',
                    component: 'jqxDropDownList',
                    label: "Type d'achat",
                    labelPosition: 'left',
                    labelWidth: '120px',
                    width: '100%',
                    options: [
                        { label: 'Achat', value: 'Achat' },
                        { label: 'Achat peliculle', value: 'Achat peliculle' }
                        
                    ]
                },
                {
                    bind: 'warehouse',
                    type: 'option',
                    component: 'jqxDropDownList',
                    label: 'Entrepôt',
                    labelPosition: 'left',
                    labelWidth: '120px',
                    width: '100%',
                    options: [
                        { label: 'Orca Packaging', value: 'Orca Packaging' },
                        { label: 'Autre Entrepôt', value: 'Autre Entrepôt' }
                    ]
                },
                { bind: 'dock', type: 'text', label: 'Porte', labelPosition: 'left', labelWidth: '120px', width: '100%' },
                { bind: 'address1', type: 'text', label: 'Adresse', labelPosition: 'left', labelWidth: '120px', width: '100%', readOnly: true },
                { bind: 'city', type: 'text', label: 'Ville', labelPosition: 'left', labelWidth: '120px', width: '100%', readOnly: true },
                { bind: 'postal_code', type: 'text', label: 'Code postal', labelPosition: 'left', labelWidth: '120px', width: '100%', readOnly: true },
                { bind: 'phone', type: 'text', label: 'Tél.', labelPosition: 'left', labelWidth: '120px', width: '100%', required: true },
                { bind: 'fax', type: 'text', label: 'Fax', labelPosition: 'left', labelWidth: '120px', width: '100%' }
            ],
            value: formData2
        });


        const formData3 = {
    order_number: '15885',
    transmit: true,
    complete: false,
    cancel: false,
    currency: 'CDN',
    payment_term: 'Net 30 jours',
    order_date: new Date('2025-05-14'),
    required_date: new Date('2025-05-26'),
    carrier: 'Prépayé /Prepaid'
};

$('#form3').jqxForm({
    template: [
        { bind: 'order_number', type: 'text', label: '# Bon achat', labelPosition: 'left', labelWidth: '130px', width: '100%', readOnly: true },

        { bind: 'transmit', type: 'boolean', label: 'Transmettre', labelPosition: 'left', labelWidth: '130px' },
        { bind: 'complete', type: 'boolean', label: 'Complet', labelPosition: 'left', labelWidth: '130px' },
        { bind: 'cancel', type: 'boolean', label: 'Annuler', labelPosition: 'left', labelWidth: '130px' },

        {
            bind: 'currency',
            type: 'option',
            component: 'jqxDropDownList',
            label: 'Devise',
            labelPosition: 'left',
            labelWidth: '130px',
            width: '100%',
            options: [
                { label: 'CDN', value: 'CDN' },
                { label: 'USD', value: 'USD' }
            ]
        },
        {
            bind: 'payment_term',
            type: 'option',
            component: 'jqxDropDownList',
            label: 'Terme de paiement',
            labelPosition: 'left',
            labelWidth: '130px',
            width: '100%',
            options: [
                { label: 'Net 30 jours', value: 'Net 30 jours' },
                { label: 'Net 60 jours', value: 'Net 60 jours' },
                { label: 'Paiement comptant', value: 'Paiement comptant' }
            ]
        },
        {
            bind: 'order_date',
            type: 'date',
            label: 'Date',
            labelPosition: 'left',
            labelWidth: '130px',
            width: '100%'
        },
        {
            bind: 'required_date',
            type: 'date',
            label: 'Date requise',
            labelPosition: 'left',
            labelWidth: '130px',
            width: '100%'
        },
        {
            bind: 'carrier',
            type: 'text',
            label: 'Transporteur',
            labelPosition: 'left',
            labelWidth: '130px',
            width: '100%'
        }
    ],
    value: formData3
});




const productDetailData = [
    {
        product_id: 'PF5035SWMROUGE22358',
        description: 'Sac Wicket Rondeau Master 50LB "Rouge"',
        supplier: 'SacConversionWicket',
        quantity: 1000,
        unit: 'Sac',
        price: 0.1
    },
    {
        product_id: 'PF5035SWMVERT22358',
        description: 'Sac Wicket Rondeau Master 50LB "Vert"',
        supplier: 'SacWicketConversion',
        quantity: 1000,
        unit: 'Sac',
        price: 0.1
    },
    {
        product_id: 'PF5035SWMJAUNE22358',
        description: 'Sac Wicket Rondeau Master 50LB "Jaune"',
        supplier: 'SacWicketConversion',
        quantity: 2000,
        unit: 'Sac',
        price: 0.1
    }
];

const productSource = {
    localdata: productDetailData,
    datatype: "array",
    datafields: [
        { name: 'product_id', type: 'string' },
        { name: 'description', type: 'string' },
        { name: 'supplier', type: 'string' },
        { name: 'quantity', type: 'number' },
        { name: 'unit', type: 'string' },
        { name: 'price', type: 'number' }
    ]
};

const productAdapter = new $.jqx.dataAdapter(productSource);

$("#productDetailGrid").jqxGrid({
    source: productAdapter,
    width: '100%',
    autoheight: true,
    columnsresize: true,
    altrows: true,
    columns: [
        { text: "# Produit", datafield: "product_id", width: "20%" },
        { text: "Description", datafield: "description", width: "30%" },
        { text: "# Fournisseur", datafield: "supplier", width: "20%" },
        { text: "Qte cmd", datafield: "quantity", width: "10%", cellsalign: 'right', align: 'right' },
        { text: "Unité cmd", datafield: "unit", width: "10%" },
        { text: "Prix", datafield: "price", width: "10%", cellsformat: 'f2', cellsalign: 'right', align: 'right' }
    ]
});


const maxHeight = Math.max(
    $('#form1 .jqx-form').outerHeight(),
    $('#form2 .jqx-form').outerHeight(),
    $('#form3 .jqx-form').outerHeight()
);

$('#form1 .jqx-form, #form2 .jqx-form, #form3 .jqx-form').css('height', maxHeight + 'px');

setTimeout(() => {
    const maxHeight = Math.max(
        $('#form1 .jqx-form').outerHeight(),
        $('#form2 .jqx-form').outerHeight(),
        $('#form3 .jqx-form').outerHeight()
    );
    $('#form1 .jqx-form, #form2 .jqx-form, #form3 .jqx-form').css('height', maxHeight + 'px');
}, 100);

    });
</script>
@endpush


