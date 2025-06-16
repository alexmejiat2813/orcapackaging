@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')

<!-- Modal -->
<div class="modal fade" id="openOrdersModal" tabindex="-1" aria-labelledby="openOrdersModal" data-bs-backdrop="static" data-bs-keyboard="false">
<div class="modal-dialog modal-extra-large modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Orders</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-2" style="max-height: 70vh; overflow-y: auto;">
      <div class="d-flex flex-column mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- Título del listado -->
                <h4 class="mb-0 ms-3"></h4>

                <x-permission-users :allowed-roles="['Thomas Admin']">
                    <div class="d-flex gap-2 align-items-center">
                        <!--button type="button" id="btnNew" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Nouvelle commande (New Order)">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button type="button" id="btnEdit" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Modifier commande (Edit Order)">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" id="btnDuplicate" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Dupliquer commande (Duplicate)">
                            <i class="bi bi-files"></i>
                        </button-->
                        <button type="button" id="syncButton" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Synchronize information">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <!--button type="button" id="btnFollowUps" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Consulter les suivis (Check Follow-Ups)">
                            <i class="bi bi-search"></i>
                        </button>
                        <button id="abrirModal1" type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Orders">
                            <i class="bi bi-tag"></i>
                        </button-->
                    </div>
                </x-permission-users>                
            </div>
        </div>
        <div style="border: none;" id="jqxTable"></div>
      </div>
      <div class="modal-footer">
        <!--button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar cambios</button-->
      </div>
    </div>
  </div>
</div>

    <div class="pagetitle">
        <h1>Production Planning</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Production Planning</li>
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
                <h4 class="mb-0 ms-3">Schedule orders</h4>
                <!-- Filtros -->
                <!--div class="d-flex flex-wrap gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmittedFirst" id="is_transmittedFirst" data-field="Commande_Transmit_First" value="transmittedFirst" checked>
                        <label class="form-check-label" for="is_transmittedFirst">Order preparation</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmitted" id="is_transmitted" data-field="Transmit" value="transmitted" checked>
                        <label class="form-check-label" for="is_transmitted">Order approval</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_complete" id="is_complete" data-field="Credit_Autorise" value="completed" checked>
                        <label class="form-check-label" for="is_complete">Authorized credit</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_canceled" id="is_canceled" data-field="isReady_Production" value="cancelled" checked>
                        <label class="form-check-label" for="is_canceled">Ready to produce</label>
                    </div>
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
                        <!--button type="button" id="btnNew" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Nouvelle commande (New Order)">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button type="button" id="btnEdit" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Modifier commande (Edit Order)">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" id="btnDuplicate" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Dupliquer commande (Duplicate)">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" id="btnRefrash" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Actualiser la liste (Refresh)">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button type="button" id="btnFollowUps" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Consulter les suivis (Check Follow-Ups)">
                            <i class="bi bi-search"></i>
                        </button-->
                        <button id="abrirModal" type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Orders">
                            <i class="bi bi-tag"></i>
                        </button>
                    </div>
                </x-permission-users>                
            </div>
        </div>

    </div>


                <div id="scheduler"></div>
                <div id="messageBox"></div>
 

@endsection
@push('styles')
<style>
  .modal-extra-large {
    max-width: 90vw;
    max-height: 90vw;
  }
</style>
@endpush

@push('scripts')
    <!-- Include jqx scripts -->
    <script src="/assets/jqwidgets/jqxcore.js"></script>
    <script src="/assets/jqwidgets/jqxbuttons.js"></script>
    <script src="/assets/jqwidgets/jqxscrollbar.js"></script>
    <script src="/assets/jqwidgets/jqxdata.js"></script>
    <script src="/assets/jqwidgets/jqxdate.js"></script>
    <script src="/assets/jqwidgets/jqxscheduler.js"></script>
    <script src="/assets/jqwidgets/jqxscheduler.api.js"></script>
    <script src="/assets/jqwidgets/jqxdatetimeinput.js"></script>
    <script src="/assets/jqwidgets/jqxmenu.js"></script>
    <script src="/assets/jqwidgets/jqxcalendar.js"></script>
    <script src="/assets/jqwidgets/jqxtooltip.js"></script>
    <script src="/assets/jqwidgets/jqxwindow.js"></script>
    <script src="/assets/jqwidgets/jqxcheckbox.js"></script>
    <script src="/assets/jqwidgets/jqxlistbox.js"></script>
    <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
    <script src="/assets/jqwidgets/jqxnumberinput.js"></script>
    <script src="/assets/jqwidgets/jqxradiobutton.js"></script>
    <script src="/assets/jqwidgets/jqxinput.js"></script>
    <script src="/assets/jqwidgets/jqxdatatable.js"></script>
    <script src="/assets/jqwidgets/jqxsplitter.js"></script>

    <!-- SweetAlert for alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global machine data -->
    <script>const machineData = @json($machines);</script>

    <!-- Import JS modules -->
    <script type="module" src="/assets/js/config.js"></script>
    <script type="module" src="/assets/js/scheduler.js"></script>

    <!--script type="text/javascript">

            $(document).ready(function () {

                $('#abrirModal').on('click', function() {
      var modal = new bootstrap.Modal(document.getElementById('openOrdersModal'));
      modal.show();
    });

        var urlSyncSchedule = "/production/orders/sync-schedule" ;
               

                var listSource = {
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
                { name: 'Lot_Id', type: 'int' },
                { name: 'Product_Id', type: 'int' },
                { name: 'PrNumber', type: 'string' },
                { name: 'PrDescription1', type: 'string' },
                { name: 'Lots_Qty', type: 'float' },
                { name: 'Qty_InStock', type: 'float' },
                { name: 'Lots_Price', type: 'float' },
                { name: 'Shipping_Qty', type: 'float' },
                { name: 'Commentaire', type: 'string' },
                { name: 'Unit_Qty', type: 'string' },
                { name: 'Unit_Price', type: 'string' },
                { name: 'SubTotal', type: 'float' },
                { name: 'Total', type: 'float' },
                { name: 'Qty_Finish', type: 'float' },
                { name: 'Transmit', type: 'boolean' },
                { name: 'Credit_Autorise', type: 'boolean' },
                { name: 'isReady_Production', type: 'boolean' },
                { name: 'IsCompletedLogic', type: 'boolean' },
                { name: 'IsCanceledLogic', type: 'boolean' },
                { name: 'Commande_Transmit_First', type: 'boolean' },
            ],
                    id: 'Commande_Id',
                    url: '/production/production/get-commandes'
                };

                let dataAdapter = new $.jqx.dataAdapter(listSource);

                $("#jqxTable").jqxGrid({
                    
   

           width:'100%',
            sortable: true,
            filterable: true,
            editable: true,
            //columnsresize: true,
            showfilterrow: true,
            source: dataAdapter,
            keyboardnavigation: false,
                        rendertoolbar: function (toolbar) {
                const container = $("<div style='margin: 5px;'></div>");
                toolbar.append(container);
                container.append('<input class="btn btn-primary" id="syncButton" type="button" value="Synchronize Schedule" />');
                $("#syncButton").jqxButton();

                $('#syncButton').on('click', function () {
                    // Remove focus from edited cell to ensure value is saved
                    $('#jqxTable').jqxGrid('endcelledit', $('#jqxTable').jqxGrid('getselectedrowindex'), "Scheduled_Date", false);

                    const rows = $('#jqxTable').jqxGrid('getrows');

                    const selectedLots = rows
                        .filter(row => row.Scheduled_Date instanceof Date && !isNaN(row.Scheduled_Date))
                        .map(row => ({
                            lot_id: row.Lot_Id,
                            commande_id: row.Commande_Id,
                            Scheduled_Date: row.Scheduled_Date.toISOString().split('T')[0] // formatted date
                        }));

                    if (selectedLots.length === 0) {
                        Swal.fire('No data', 'No lots with a scheduled date to synchronize.', 'info');
                        return;
                    }

                    // Disable button and show loading
                    $("#syncButton").prop("disabled", true).val("Synchronizing...");

                    Swal.fire({
                        title: 'Synchronizing...',
                        text: 'Please wait while we update the schedule.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(urlSyncSchedule, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-TOKEN": window.csrfToken
                        },
                        body: JSON.stringify({ lots: selectedLots })
                    })
                        .then(response => response.json())
                        .then(result => {
                            Swal.fire('Done', `Synchronization complete. Changes made: ${result.updated}`, 'success');
                            $('#jqxTable').jqxGrid('updatebounddata');
                        })
                        .catch(error => {
                            console.error('Error syncing:', error);
                            Swal.fire('Error', 'An error occurred during synchronization.', 'error');
                        })
                        .finally(() => {
                            $("#syncButton").prop("disabled", false).val("Synchronize Schedule");
                        });
                });
            },
            enabletooltips: true,
            contextmenuenabled: true,
            showgroupsheader: true,
                    columns: [
                        { text: 'Customer Code', dataField: 'Customer_Code', width: '5%', align: 'center', cellsalign: 'center', editable: false },
                        { text: 'Customer', dataField: 'Customer_Name', width: '20%', align: 'center', editable: false },
                        { text: 'CMD', dataField: 'InInvoiceNumber', width: '5%', align: 'center', cellsalign: 'center', editable: false },
                        { text: 'Lot Id', dataField: 'Lot_Id', width: '5%', align: 'center', cellsalign: 'center', editable: false },
                        { text: 'Product', dataField: 'PrDescription1', width: '55%', align: 'center', editable: false },
                        { text: 'Scheduled Date', dataField: 'Scheduled_Date', cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', width: '10%', align: 'center', editable: true },
                        { text: "Commande_Transmit_First", datafield: "Commande_Transmit_First", width: '5%', hidden: true, editable: false },
                { text: "Transmit", datafield: "Transmit", width: '5%', hidden: true, editable: false },
                { text: "Credit Autorise", datafield: "Credit_Autorise", width: '5%', hidden: true, editable: false },
                { text: "isReady Production", datafield: "isReady_Production", width: '5%', hidden: true, editable: false },
                { text: "Complet", datafield: "IsCompletedLogic", width: '5%', hidden: true, editable: false },
                { text: "Cancel", datafield: "IsCanceledLogic", width: '5%', hidden: true, editable: false },
                        
                    ],
            ready: function () {
                // Aplicar filtros por defecto al cargar el grid
                const filters = [
                    { field: "Commande_Transmit_First", value: true },
                    { field: "Transmit", value: true },
                    { field: "Credit_Autorise", value: true },
                    { field: "isReady_Production", value: true },
                    { field: "IsCompletedLogic", value: false },
                    { field: "IsCanceledLogic", value: false }
                ];
                filters.forEach(filter => {
                    let filterGroup = new $.jqx.filter();
                    let value = filter.value;
                    let filterCondition = filterGroup.createfilter('booleanfilter', value, 'equal');
                    filterGroup.addfilter(1, filterCondition);
                    $("#jqxTable").jqxGrid('addfilter', filter.field, filterGroup);
                });
                $("#jqxTable").jqxGrid('applyfilters');
            }
                });
                /*$('#jqxTable').on('rowdoubleclick', function (event) {

                    alert("The row you clicked twice is: " + event.args.datafield );
                });*/
            });
        </script-->
@endpush

