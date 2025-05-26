@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>Follow-Up Purchase Orders</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Follow-Up</li>
            </ol>
        </nav>
    </div>

    <div id="followUpGridSection">
        <div class="d-flex flex-column mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">

                <!-- Botonera -->
                 <x-permission-users :allowed-roles="['Thomas Admin']">
                <div class="d-flex gap-2">
                    <button id="btnRefreshGrid" type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh Data">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                 </x-permission-users>

                <h4 class="mb-0 ms-3">List of unbilled purchase orders</h4>

                <x-permission-users :allowed-roles="['Thomas Admin']">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                        <!-- Filtros booleanos -->
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
                 </x-permission-users>

            </div>
        </div>

        <div id="splitter">
            <div class="splitter-panel">
                <div style="border: none;" id="jqxlistbox"></div>
            </div>
            <div class="splitter-panel">
                <div style="overflow: hidden;" id="gridFollowUp"></div>
            </div>
        </div>
    </div>

    <div id="followUpFormSection" style="display: none;">

        <div class="d-flex flex-column mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">

                <h4 class="mb-0 ms-3">Follow-Up Entry</h4>

            </div>
        </div>

        <div style="padding: 10px;">
            
            <!-- Formulario de nuevo seguimiento -->
            <form id="followUpForm" class="p-3">
                <input type="hidden" id="po_id" name="po_id" />

                <div class="mb-3">
                    <label for="status_id" class="form-label">Status:</label>
                    <select id="status_id" name="status_id" class="form-select"></select>
                </div>
                <div class="mb-3">
                    <label for="follow_up_notes" class="form-label">Follow-Up Notes:</label>
                    <textarea id="follow_up_notes" name="follow_up_notes" rows="4" class="form-control" placeholder="Write your notes here..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button type="button" id="btnSaveFollowUp" class="btn btn-primary"> Save </button>
                    <button type="button" class="btn btn-secondary" id="btnCancelFollowUp">Cancel</button>
                </div>
            </form>

            <!-- Grid de historial -->
            <hr />
            <div class="d-flex flex-column mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">

                    <h4 class="mb-0 ms-3">List of actions taken for this purchase order (PO)</h4>

                </div>
            </div>
            <div id="gridFollowUpHistory" style="margin-bottom: 20px;"></div>
        </div>
    </div>
@endsection

@push('scripts')
 <script src="/assets/jqwidgets/jqxsplitter.js"></script>
<script>
    $(document).ready(function () {

        $("#splitter").jqxSplitter({ width: '100%', height: 640, panels: [{ size: 200, min: 100 }, {min: 200, size: 400, collapsible: false}] })

        let source = {
            datatype: "json",
            url: "/purchasing/purchase-followup",
            datafields: [
                { name: "PO_ID", type: "int" },
                { name: "PO_No", type: "string" },
                { name: "PO_Date", type: "date" },
                { name: "PO_Note", type: "string" },
                { name: "Supplier_No", type: "string" },
                { name: "Supplier_Name", type: "string" },
                { name: "Product_Supplier_Code", type: "string" },
                { name: "Product_ID", type: "string" },
                { name: "PrNumber", type: "string" },
                { name: "PrDescription1", type: "string" },
                { name: "Order_Quantity", type: "number" },
                { name: "Order_Price", type: "number" },
                { name: "Receiving_No", type: "string" },
                { name: "Receiving_Date", type: "date" },
                { name: "Receiving_Detail_Qty", type: "number" },
                { name: "Supplier_Invoice_Id", type: "string" },
                { name: "Supplier_Invoice_Date", type: "date" },
                { name: "Supplier_Invoice_Detail_Qty", type: "number" },
                { name: "Supplier_Invoice_Detail_Price", type: "number" },
                { name: "Unit_Qty", type: "string" },
                { name: "Unit_Price", type: "string" },
                { name: "User_PO", type: "string" },
                { name: "User_Receiving", type: "string" },
                { name: "User_Invoice", type: "string" },
                { name: "Days_To_Receiving", type: "number" },
                { name: "Days_To_Invoice", type: "number" },
                { name: "Days_Since_Invoice", type: "number" }
            ]
        };

        let dataAdapter = new $.jqx.dataAdapter(source);

        $("#gridFollowUp").jqxGrid({
            width: '100%',
            pageable: true,
            pagermode: "simple",
            height: 640,
            sortable: true,
            filterable: true,
            columnsresize: true,
            showfilterrow: true,
            pageSize: 15,
            source: dataAdapter,
            keyboardnavigation: false,
            enabletooltips: true,
            contextmenuenabled: true,
            showgroupsheader: true,
            columns: [
                { text: "Supplier Code", datafield: "Supplier_No", columnGroup: 'supplier', width: '7%', align: 'center', cellsalign: 'center' },
                { text: "Supplier Name", datafield: "Supplier_Name", columnGroup: 'supplier', width: '15%', align: 'center' },
                { text: "PO", datafield: "PO_No", columnGroup: 'order', width: '6%', align: 'center', cellsalign: 'center' },
                { text: "PO User", datafield: "User_PO", columnGroup: 'order', width: '10%', align: 'center', cellsalign: 'center' },
                { text: "PO Date", datafield: "PO_Date", columnGroup: 'order', width: '7%', columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', filtertype: 'range' },
                { text: "Order Qty", datafield: "Order_Quantity", columnGroup: 'order', width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Unit Qty", datafield: "Unit_Qty", columnGroup: 'order', width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Order Price", datafield: "Order_Price", columnGroup: 'order', width: '10%', cellsFormat: 'c2', align: 'center', cellsalign: 'center' },
                { text: "Unit Price", datafield: "Unit_Price", columnGroup: 'order', width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Receiving Code", datafield: "Receiving_No", columnGroup: 'receiving', width: '7%', align: 'center', cellsalign: 'center' },
                { text: "Receiving User", datafield: "User_Receiving", columnGroup: 'receiving', width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Receiving Date", datafield: "Receiving_Date", columnGroup: 'receiving', width: '7%', columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', filtertype: 'range' },
                { text: "Days Receiving", datafield: "Days_To_Receiving", columnGroup: 'receiving', width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Receiving Qty", datafield: "Receiving_Detail_Qty", columnGroup: 'receiving', width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Invoice Code", datafield: "Supplier_Invoice_Id", columnGroup: 'invoice', width: '7%', align: 'center', cellsalign: 'center' },
                { text: "Invoice User", datafield: "User_Invoice", columnGroup: 'invoice', width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Invoice Date", datafield: "Supplier_Invoice_Date", columnGroup: 'invoice', width: '7%', columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', filtertype: 'range' },
                { text: "Days Invoice", datafield: "Days_To_Invoice", columnGroup: 'invoice', width: '6%', align: 'center', cellsalign: 'center' },
                { text: "Invoice Qty", datafield: "Supplier_Invoice_Detail_Qty", columnGroup: 'invoice', width: '10%', align: 'center', cellsalign: 'center' },
                { text: "Invoice Price", datafield: "Supplier_Invoice_Detail_Price", columnGroup: 'invoice', width: '10%', cellsFormat: 'c2', align: 'center', cellsalign: 'center' },
                { text: "Product Supplier", datafield: "Product_Supplier_Code", columnGroup: 'product', width: '15%', align: 'center' },
                { text: "Product Code", datafield: "PrNumber", columnGroup: 'product', width: '15%', align: 'center' },
                { text: "Product Name", datafield: "PrDescription1", columnGroup: 'product', width: '40%', align: 'center' },
                { text: "PO Note", datafield: "PO_Note", width: '200%' }
            ],
            columnGroups: [
                { text: 'Order Info', name: 'order', align: 'center' },
                { text: 'Supplier Info', name: 'supplier', align: 'center' },
                { text: 'Receiving Info', name: 'receiving', align: 'center' },
                { text: 'Invoice Info', name: 'invoice', align: 'center' },
                { text: 'Product Info', name: 'product', align: 'center' }
            ]
        });

        var listSource = [
            // Supplier Info
            { label: 'Supplier Code', value: 'Supplier_No', checked: true },
            { label: 'Supplier Name', value: 'Supplier_Name', checked: true },

            // Order Info
            { label: 'PO', value: 'PO_No', checked: true },
            { label: 'PO User', value: 'User_PO', checked: true },
            { label: 'PO Date', value: 'PO_Date', checked: true },
            { label: 'Order Qty', value: 'Order_Quantity', checked: true },
            { label: 'Unit Qty', value: 'Unit_Qty', checked: true },
            { label: 'Order Price', value: 'Order_Price', checked: true },
            { label: 'Unit Price', value: 'Unit_Price', checked: true },

            // Receiving Info
            { label: 'Receiving Code', value: 'Receiving_No', checked: true },
            { label: 'Receiving User', value: 'User_Receiving', checked: true },
            { label: 'Receiving Date', value: 'Receiving_Date', checked: true },
            { label: 'Days Receiving', value: 'Days_To_Receiving', checked: true },
            { label: 'Receiving Qty', value: 'Receiving_Detail_Qty', checked: true },

            // Invoice Info
            { label: 'Invoice Code', value: 'Supplier_Invoice_Id', checked: true },
            { label: 'Invoice User', value: 'User_Invoice', checked: true },
            { label: 'Invoice Date', value: 'Supplier_Invoice_Date', checked: true },
            { label: 'Days Invoice', value: 'Days_To_Invoice', checked: true },
            { label: 'Invoice Qty', value: 'Supplier_Invoice_Detail_Qty', checked: true },
            { label: 'Invoice Price', value: 'Supplier_Invoice_Detail_Price', checked: true },

            // Product Info
            { label: 'Product Supplier Code', value: 'Product_Supplier_Code', checked: true },
            { label: 'Product Code', value: 'PrNumber', checked: true },
            { label: 'Product Name', value: 'PrDescription1', checked: true },

            // Other
            { label: 'PO Note', value: 'PO_Note', checked: true }
        ];


        $("#jqxlistbox").jqxListBox({ source: listSource, width: 200, height: 640, checkboxes: true });
        $("#jqxlistbox").on('checkChange', function (event) {
            $("#gridFollowUp").jqxGrid('beginupdate');
            if (event.args.checked) {
                $("#gridFollowUp").jqxGrid('showcolumn', event.args.value);
            } else {
                $("#gridFollowUp").jqxGrid('hidecolumn', event.args.value);
            }
            $("#gridFollowUp").jqxGrid('endupdate');
        });

        function loadFollowUpLogs(poId) {
            $.ajax({
                url: '/purchasing/follow-up/logs',
                method: 'GET',
                data: { po_id: poId },
                success: function (response) {
                    if (response.success) {
                        const source = {
                            datatype: "json",
                            localdata: response.data,
                            datafields: [
                                { name: 'PO_FollowUp_ID', type: 'int' },
                                { name: 'Followup_Date', type: 'date' },
                                { name: 'Followup_By', type: 'int' },
                                { name: 'Followup_Notes', type: 'string' },
                                { name: 'Status_ID', type: 'int' },
                                { name: 'User_Name', type: 'string' },
                                { name: 'Status_Name', type: 'string' }
                            ]
                        };

                        const adapter = new $.jqx.dataAdapter(source);

                        $("#gridFollowUpHistory").jqxGrid({
                            width: '100%',
                            height: 200,
                            pageSize: 5,
                            columnsautoresize: true,
                            pageable: true,
                            source: adapter,
                            columnsresize: true,
                            columns: [
                                { text: 'Date', datafield: 'Followup_Date', width: '8%', columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center' },
                                { text: 'By', datafield: 'User_Name', width: '10%', align: 'center', cellsalign: 'center' },
                                { text: 'Status', datafield: 'Status_Name', width: '20%', align: 'center', cellsalign: 'center' },
                                { text: 'Notes', datafield: 'Followup_Notes', width: '125%' }
                            ]
                        });
                    }
                }
            });
        }

        function loadFollowUpStatuses() {
            $.get("/purchasing/follow-up/statuses", function (response) {
                if (response.success) {
                    const $select = $("#status_id");
                    $select.empty();
                    response.data.forEach(function (status) {
                        $select.append($("<option>", {
                            value: status.Status_ID,
                            text: status.Status_Name
                        }));
                    });
                }
            });
        }

        $("#gridFollowUp").on("rowdoubleclick", function (event) {
            const rowData = $("#gridFollowUp").jqxGrid("getrowdata", event.args.rowindex);

            $("#po_id").val(rowData.PO_ID);
            $("#follow_up_by").val("");
            $("#status_id").val("1");
            $("#follow_up_notes").val("");

            isFormDirty = false;

            loadFollowUpStatuses();
            loadFollowUpLogs(rowData.PO_ID);

            $("#followUpGridSection").hide();
            $("#followUpFormSection").show();
        });

        let isFormDirty = false;

        // Detectar cambios en los campos
        $("#followUpForm input, #followUpForm textarea, #followUpForm select").on("input change", function () {
            isFormDirty = true;
        });

        // Protección contra recarga (F5, cerrar pestaña)
        window.addEventListener("beforeunload", function (e) {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = ""; // activa el diálogo del navegador
            }
        });

        // Botón Cancelar con confirmación
        $("#btnCancelFollowUp").on("click", function () {
            if (isFormDirty) {
                if (!confirm("Vous avez des modifications non sauvegardées. Voulez-vous vraiment quitter ce formulaire ?")) {
                    return;
                }
            }

            // Volver al grid
            $("#followUpGridSection").show();
            $("#followUpFormSection").hide();
            isFormDirty = false;
        });

        // Inicializar tooltips Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Botón para recargar datos del grid
        $("#btnRefreshData").on("click", function () {
            $("#gridFollowUp").jqxGrid("updatebounddata");
        });

        $("#btnSaveFollowUp").on("click", function () {
            let notes = $("#follow_up_notes").val().trim();

            if (notes === "") {
                alert("Please enter follow-up notes before saving.");
                $("#follow_up_notes").focus();
                return;
            }

            let formData = {
                po_id: $("#po_id").val(),
                status_id: $("#status_id").val(),
                follow_up_notes: notes,
            };

            $.ajax({
                url: "/purchasing/follow-up/store",
                type: "POST",
                data: formData,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {
                    if (response.success) {
                        alert("Saved!");
                        isFormDirty = false;
                        $("#followUpFormSection").hide();
                        $("#followUpGridSection").show();
                        loadFollowUpLogs(formData.po_id); // recarga el historial
                        $("#follow_up_notes").val(""); // limpia el textarea
                    } else {
                        alert("Failed to save: " + response.message);
                    }
                },
                error: function (xhr) {
                    alert("Server error: " + xhr.responseText);
                }
            });
        });


    });
</script>
@endpush
