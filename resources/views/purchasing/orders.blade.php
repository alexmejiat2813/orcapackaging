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
                        <button type="button" id="btnNew" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Nouvelle commande (New Order)">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button type="button" id="btnEdit" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Modifier commande (Edit Order)">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" id="btnDuplicate" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Dupliquer commande (Duplicate)">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" id="btnRefresh" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Actualiser la liste (Refresh)">
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
        <form id="purchaseOrderForm">

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Purchase Order Form</h5>
                    <div class="mt-4 d-flex justify-content-end gap-2 mb-3">
                        <button type="button" id="btnSave" class="btn btn-primary btn-sm rounded shadow-sm">  Save </button>
                        <button type="button" id="btnCancel" class="btn btn-secondary btn-sm rounded shadow-sm"> <i class="bi bi-x-circle"></i> Cancel </button>
                    </div>            
                </div>

                <div class="row gx-3">
                    <!-- Columna 1: Proveedor -->
                    <div class="col-lg-4 col-md-6">
                        <div class="bg-light p-2 border rounded small">
                            <div class="mb-2 row">
                            <label for="supplier_id" class="col-sm-4 col-form-label col-form-label-sm"># Fournisseur *</label>
                            <div class="col-sm-8">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="supplier_id" name="supplier_id" required readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="changeSupplierBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Changer de fournisseur">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                            <div class="mb-2 row">
                                <label for="supplier_name" class="col-sm-4 col-form-label col-form-label-sm">Fournisseur *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="supplier_name" name="supplier_name" required>
                                </div>
                            </div>

          <div class="mb-2 row">
            <label for="contact_person" class="col-sm-4 col-form-label col-form-label-sm">A/s</label>
            <div class="col-sm-8">
              <select class="form-select form-select-sm" id="contact_person" name="contact_person">
                <option value="Andre Begin">Andre Begin</option>
                <option value="Autre">Autre</option>
              </select>
            </div>
          </div>

          <div class="mb-2 row">
            <label for="address1" class="col-sm-4 col-form-label col-form-label-sm">Adresse *</label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="address1" name="address1" required>
            </div>
          </div>

          <div class="mb-2 row">
            <label for="address2" class="col-sm-4 col-form-label col-form-label-sm">Adresse 2</label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="address2" name="address2">
            </div>
          </div>

          <div class="mb-2 row">
            <label for="city" class="col-sm-4 col-form-label col-form-label-sm">Ville *</label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="city" name="city" required>
            </div>
          </div>

          <div class="mb-2 row">
            <label for="postal_code" class="col-sm-4 col-form-label col-form-label-sm">Code postal *</label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" required>
            </div>
          </div>

          <div class="mb-2 row">
            <label for="phone" class="col-sm-4 col-form-label col-form-label-sm">Tél. *</label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="phone" name="phone" required>
            </div>
          </div>

          <div class="mb-2 row">
            <label for="fax" class="col-sm-4 col-form-label col-form-label-sm">Fax</label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="fax" name="fax">
            </div>
          </div>
        </div>
      </div>
 <div class="col-lg-4 col-md-6">
<div class="bg-light p-2 border rounded small">
  <div class="mb-2 row">
    <label for="buyer" class="col-sm-4 col-form-label col-form-label-sm">Acheteur</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="buyer" name="buyer">
    </div>
  </div>

  <div class="mb-2 row">
    <label for="type" class="col-sm-4 col-form-label col-form-label-sm">Type d'achat</label>
    <div class="col-sm-8">
      <select class="form-select form-select-sm" id="type" name="type">
        <option value="Achat">Achat</option>
        <option value="Achat peliculle">Achat peliculle</option>
      </select>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="warehouse" class="col-sm-4 col-form-label col-form-label-sm">Entrepôt</label>
    <div class="col-sm-8">
      <select class="form-select form-select-sm" id="warehouse" name="warehouse">
        <option value="Orca Packaging">Orca Packaging</option>
        <option value="Autre Entrepôt">Autre Entrepôt</option>
      </select>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="dock" class="col-sm-4 col-form-label col-form-label-sm">Porte</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="dock" name="dock">
    </div>
  </div>

  <div class="mb-2 row">
    <label for="address1" class="col-sm-4 col-form-label col-form-label-sm">Adresse</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="address1" name="address1" readonly>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="city" class="col-sm-4 col-form-label col-form-label-sm">Ville</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="city" name="city" readonly>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="postal_code" class="col-sm-4 col-form-label col-form-label-sm">Code postal</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" readonly>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="phone" class="col-sm-4 col-form-label col-form-label-sm">Tél. *</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="phone" name="phone" required>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="fax" class="col-sm-4 col-form-label col-form-label-sm">Fax</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="fax" name="fax">
    </div>
  </div>
</div>

  </div>
  <div class="col-lg-4 col-md-12">
   <div class="bg-light p-2 border rounded small">
  <div class="mb-2 row">
    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm"># Bon achat</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="order_number" name="order_number" readonly>
    </div>
  </div>

  <div class="mb-2 row">
    <label class="col-sm-4 col-form-label col-form-label-sm">Transmettre</label>
    <div class="col-sm-8">
      <input class="form-check-input" type="checkbox" id="transmit" name="transmit">
    </div>
  </div>

  <div class="mb-2 row">
    <label class="col-sm-4 col-form-label col-form-label-sm">Complet</label>
    <div class="col-sm-8">
      <input class="form-check-input" type="checkbox" id="complete" name="complete">
    </div>
  </div>

  <div class="mb-2 row">
    <label class="col-sm-4 col-form-label col-form-label-sm">Annuler</label>
    <div class="col-sm-8">
      <input class="form-check-input" type="checkbox" id="cancel" name="cancel">
    </div>
  </div>

  <div class="mb-2 row">
    <label for="currency" class="col-sm-4 col-form-label col-form-label-sm">Devise</label>
    <div class="col-sm-8">
      <select class="form-select form-select-sm" id="currency" name="currency">
        <option value="CDN">CDN</option>
        <option value="USD">USD</option>
      </select>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="payment_term" class="col-sm-4 col-form-label col-form-label-sm">Terme de paiement</label>
    <div class="col-sm-8">
      <select class="form-select form-select-sm" id="payment_term" name="payment_term">
        <option value="Net 30 jours">Net 30 jours</option>
        <option value="Net 60 jours">Net 60 jours</option>
        <option value="Paiement comptant">Paiement comptant</option>
      </select>
    </div>
  </div>

  <div class="mb-2 row">
    <label for="order_date" class="col-sm-4 col-form-label col-form-label-sm">Date</label>
    <div class="col-sm-8">
      <input type="date" class="form-control form-control-sm" id="order_date" name="order_date">
    </div>
  </div>

  <div class="mb-2 row">
    <label for="required_date" class="col-sm-4 col-form-label col-form-label-sm">Date requise</label>
    <div class="col-sm-8">
      <input type="date" class="form-control form-control-sm" id="required_date" name="required_date">
    </div>
  </div>

  <div class="mb-2 row">
    <label for="carrier" class="col-sm-4 col-form-label col-form-label-sm">Transporteur</label>
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" id="carrier" name="carrier">
    </div>
  </div>
</div>

  </div>


<div class="mt-4">
  <label class="form-label fw-bold">Note</label>
  <textarea class="form-control" rows="4" style="white-space: pre-wrap;"></textarea>
</div>

<div class="mt-4">
  <div id="productDetailGrid"></div>
</div>


</form>

    </div>

@endsection

@push('scripts')
<script src="/assets/jqwidgets/jqxgrid.columnsreorder.js"></script>
<script src="/assets/jqwidgets/jqxform.js"></script>
<script src="/assets/jqwidgets/jqxtextarea.js"></script>
<script>
    $(document).ready(function () {

        let isFormDirty = false;

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
            $('#transmit').prop('checked', rowData.PO_Transmit);
            $('#complete').prop('checked', rowData.PO_Completed);
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

        $("#btnRefresh").click(function () {
            $("#gridFollowUp").jqxGrid({ source: getAdapter() });
            $("#gridFollowUp").jqxGrid('updatebounddata', 'cells');
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


