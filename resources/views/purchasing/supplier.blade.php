@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <!-- Page Title -->
    <div class="pagetitle">
        <h1>Suppliers</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Suppliers</li>
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
                <h4 class="mb-0 ms-3">List of suppliers</h4>

                <!-- Filtros -->
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" id="is_actived" data-field="Supplier_Active" value="actived" checked>
                        <label class="form-check-label" for="is_actived">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" id="is_blocked" data-field="Supplier_Lock" value="barred">
                        <label class="form-check-label" for="is_blocked">Barré</label>
                    </div>
                </div>
                <!-- Botones -->
                <x-permission-users :allowed-roles="['Thomas Admin']">
                    <div class="d-flex gap-2">
                        <button type="button" id="btnNew" class="btn btn-outline-primary btn-sm rounded shadow-sm" title="Add Supplier">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button type="button" id="btnEdit" class="btn btn-outline-primary btn-sm rounded shadow-sm" title="Edit Supplier">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" id="btnDuplicate" class="btn btn-outline-primary btn-sm rounded shadow-sm" title="Clone Supplier">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" title="Refresh Table">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button type="button" id="btnSupplierContacts" class="btn btn-outline-primary btn-sm rounded shadow-sm" title="View Supplier Contacts">
                            <i class="bi bi-person-lines-fill"></i>
                        </button>
                        <button type="button" id="btnFollowUps" class="btn btn-outline-primary btn-sm rounded shadow-sm" title="Consulter les suivis">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </x-permission-users>

                
            </div>
        </div>

        <!-- Grid principal -->
        <div id="gridSuppliers"></div>
    </div>

    <!-- Formulario oculto de seguimiento -->
    <div id="followUpFormSection" style="display: none;">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">
                    <i class="fas fa-user"></i> Contact Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="company-tab" data-bs-toggle="tab" data-bs-target="#company-tab-pane" type="button" role="tab">
                    <i class="fas fa-building"></i> Record
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane" type="button" role="tab">
                    <i class="fas fa-users-cog"></i> Email
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules-tab-pane" type="button" role="tab">
                    <i class="fas fa-th-large"></i> Credit Status
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content p-4 border border-top-0 bg-white rounded-bottom" id="settingsTabContent">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel">
                @include('components.purchase-order-form')
            </div>

            <!-- Company Tab -->
            <div class="tab-pane fade" id="company-tab-pane" role="tabpanel">
                <h4 class="mb-3">Company Info</h4>
                <p>Update company name, address, logo, and branding preferences.</p>
                <a href="#" class="btn btn-primary">Edit Company Info</a>
            </div>

            <!-- Users & Roles Tab -->
            <div class="tab-pane fade" id="users-tab-pane" role="tabpanel">
                <h4 class="mb-3">Users & Roles</h4>
                <p>Manage user accounts, assign roles and control access permissions.</p>
                <a href="#" class="btn btn-primary me-2">Manage Users</a>
                <a href="#" class="btn btn-secondary">Manage Roles</a>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="/assets/jqwidgets/jqxgrid.columnsreorder.js"></script>
<script src="/assets/jqwidgets/jqxform.js"></script>
<script src="/assets/jqwidgets/jqxtextarea.js"></script>
<script src="/assets/jqwidgets/jqxtabs.js"></script>
<script>
    $(document).ready(function () {

        let isFormDirty = false;
        let source = {
            datatype: "json",
            url: "/purchasing/suppliers/data",
            datafields: [
                { name: "Supplier_No", type: "int" },
                { name: "Supplier_Name", type: "string" },
                { name: "Supplier_CareOf", type: "string" },
                { name: "Supplier_Address", type: "string" },
                { name: "Supplier_City", type: "string" },
                { name: "Supplier_PostalCode", type: "string" },
                { name: "Supplier_PhoneNumber1", type: "string" },
                { name: "Supplier_Email", type: "string" },                
                { name: "Supplier_SortKey", type: "string" },
                { name: "Supplier_ISOCountryCode", type: "string" },
                { name: "Supplier_WebAddress", type: "string" },
                { name: "Supplier_Active", type: "bool" },
                { name: "Supplier_Lock", type: "bool" },
            ],
        };

        let dataAdapter = new $.jqx.dataAdapter(source);

        $("#gridSuppliers").jqxGrid({
            width: '100%',
            height : 650,
            //autoheight: true,
            //pageable: true,
            sortable: true,
            filterable: true,
            showfilterrow: true,
            source: dataAdapter,
            columnsresize: true,
            columnsreorder: true,
            groupable: true, showgroupsheader: true,
            //pageSize: 17,
            //pagermode: "simple",
            columns: [
                { text: "Supplier Code", datafield: "Supplier_No", width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Supplier", datafield: "Supplier_Name", width: '15%', align: 'center' },
                { text: "Key", datafield: "Supplier_SortKey", width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Contact", datafield: "Supplier_CareOf", width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Adress", datafield: "Supplier_Address", width: '17%', align: 'center' },
                { text: "City", datafield: "Supplier_City", width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Country", datafield: "Supplier_ISOCountryCode", width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Postal Code", datafield: "Supplier_PostalCode", width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Phone", datafield: "Supplier_PhoneNumber1", width: '8%', align: 'center', cellsalign: 'center' },
                { text: "Email", datafield: "Supplier_Email", width: '16%', align: 'center' },
                { text: "Web", datafield: "Supplier_WebAddress", width: '16%', align: 'center' },
                { text: "Active", datafield: "Supplier_Active", hidden: true },
                { text: "Look", datafield: "Supplier_Lock", hidden: true },
            ],
            ready: function () {
                // Aplicar filtros por defecto al cargar el grid
                const filters = [
                    { field: "Supplier_Active", value: true },
                    { field: "Supplier_Lock", value: false }
                ];
                filters.forEach(filter => {
                    let filterGroup = new $.jqx.filter();
                    let value = filter.value;
                    let filterCondition = filterGroup.createfilter('booleanfilter', value, 'equal');
                    filterGroup.addfilter(1, filterCondition);
                    $("#gridSuppliers").jqxGrid('addfilter', filter.field, filterGroup);
                });
                $("#gridSuppliers").jqxGrid('applyfilters');
            }
        });



        // Filtros dinámicos al cambiar los checkboxes
        $(".status-filter").on("change", function () {
            const field = $(this).data("field");
            const isChecked = $(this).is(":checked");

            $("#gridSuppliers").jqxGrid('removefilter', field);

            let filterGroup = new $.jqx.filter();
            let value = isChecked ? true : false;
            let filter = filterGroup.createfilter('booleanfilter', value, 'equal');
            filterGroup.addfilter(1, filter);
            $("#gridSuppliers").jqxGrid('addfilter', field, filterGroup);

            $("#gridSuppliers").jqxGrid('applyfilters');
        });















        // Función para obtener la fila seleccionada
        function getSelectedRowData() {
            const selectedRowIndex = $("#gridSuppliers").jqxGrid('getselectedrowindex');
            if (selectedRowIndex === -1) {
                // ⚠️ Alerta si no hay fila seleccionada
                $("#messageBox").html("⚠️ Por favor, seleccione una fila primero.").fadeIn().delay(2000).fadeOut();
                return null;
            }
            return $("#gridSuppliers").jqxGrid('getrowdata', selectedRowIndex);
        }

        $("#btnNew").on('click', function () {
            isFormDirty = false;
            $("#followUpGridSection").hide();
            $("#followUpFormSection").show();

            // Limpiar todos los campos del formulario
            $('#purchaseOrderForm')[0].reset();

            // Establecer fechas por defecto (hoy)
            const today = new Date().toISOString().split('T')[0];
            $('#order_date').val(today);
            $('#required_date').val(today);

            // Limpiar el grid de productos
            $("#productDetailGrid").jqxGrid("clear");
        });

        $("#btnEdit").on('click', function () {
            const rowData = getSelectedRowData();
            isFormDirty = false;
            if (!rowData) return;

            $("#followUpGridSection").hide();
            $("#followUpFormSection").show();

            // Asignar datos al formulario
            $('#supplier_id').val(rowData.Supplier_No);
            $('#order_number').val(rowData.PO_No);
            $('#transmit').prop('checked', rowData.Supplier_Active);
            $('#complete').prop('checked', rowData.Supplier_Look);
            $('#cancel').prop('checked', rowData.PO_Cancel);

            const orderDate = rowData.PO_Date ? new Date(rowData.PO_Date).toISOString().split('T')[0] : '';
            const requiredDate = rowData.PO_Date_Reception ? new Date(rowData.PO_Date_Reception).toISOString().split('T')[0] : '';
            $('#order_date').val(orderDate);
            $('#required_date').val(requiredDate);

            // Obtener información del proveedor
            const supplierNo = rowData.Supplier_No;
            if (!supplierNo) return;

            $.get(`/purchasing/suppliers/${supplierNo}`, function (data) {
                $('#supplier_id').val(data.supplier_id).prop('readonly', true);
                $('#supplier_name').val(data.supplier_name).prop('disabled', true);
                $('#contact_person').val(data.contact_person).prop('disabled', true);
                $('#address1').val(data.address1).prop('readonly', true);
                $('#address2').val(data.address2).prop('readonly', true);
                $('#city').val(data.city).prop('readonly', true);
                $('#postal_code').val(data.postal_code).prop('readonly', true);
                $('#phone').val(data.phone).prop('readonly', true);
                $('#fax').val(data.fax).prop('readonly', true);
            }).fail(function () {
                alert("Proveedor no encontrado.");
            });

            // Obtener contactos del proveedor
            $.get(`/purchasing/suppliers/${supplierNo}/contacts`, function (data) {
                const contactSelect = $('#contact_person');
                contactSelect.empty();
                if (data.contacts && data.contacts.length > 0) {
                    data.contacts.forEach(function (contact) {
                        const fullName = `${contact.Asset_FName} ${contact.Asset_Name}`;
                        contactSelect.append(new Option(fullName, contact.Asset_ID));
                    });
                } else {
                    contactSelect.append(new Option('Sin contactos disponibles', ''));
                }
            }).fail(function () {
                alert("No se pudieron cargar los contactos del proveedor.");
            });
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

        $("#btnSave").on('click', function () {
            const isForm1Valid = $('#form1').jqxForm('validate');
            const isForm2Valid = $('#form2').jqxForm('validate');
            const isForm3Valid = $('#form3').jqxForm('validate');

            if (!isForm1Valid || !isForm2Valid || !isForm3Valid) {
                alert("Por favor, completa todos los campos requeridos.");
                return;
            }

        });

        $("#btnCancel").on("click", function () {
            if (isFormDirty) {
                if (!confirm("Vous avez des modifications non sauvegardées. Voulez-vous vraiment quitter ce formulaire ?")) {
                    return;
                }
            }

            // Volver al grid
            // Restaurar vista del grid
            $("#followUpFormSection").hide();
            $("#followUpGridSection").show();

            // Resetear formulario y estado
            $('#form1').jqxForm('val', {});
            $('#form2').jqxForm('val', {});
            $('#form3').jqxForm('val', {});
            $("#productDetailGrid").jqxGrid("clear");
            isFormDirty = false;
        });

        


        







        // Detectar cambios en los campos
        $("#form1 input, #form1 select, #form2 input, #form2 select, #form3 input, #form3 select").on("input change", function () {
            isFormDirty = true;
        });

        // Protección contra recarga (F5, cerrar pestaña)
        window.addEventListener("beforeunload", function (e) {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = ""; // activa el diálogo del navegador
            }
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

$(function () {
    $('[data-bs-toggle="tooltip"]').each(function () {
        new bootstrap.Tooltip(this);
    });
});

$('#changeSupplierBtn').on('click', function () {
    $('#supplier_id').prop('readonly', false).focus();
});

    });

    $(document).on('input', '#supplier_id', function () {
    const supplierNo = $(this).val();
    if (!supplierNo) return;

    $.get(`/purchasing/suppliers/${supplierNo}`, function (data) {
        $('#supplier_name').val(data.supplier_name);
        $('#contact_person').val(data.contact_person);
        $('#address1').val(data.address1);
        $('#address2').val(data.address2);
        $('#city').val(data.city);
        $('#postal_code').val(data.postal_code);
        $('#phone').val(data.phone);
        $('#fax').val(data.fax);
    }).fail(function () {
        alert("Proveedor no encontrado.");
    });
});

</script>

@endpush


