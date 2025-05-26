@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')

    <div class="pagetitle">
        <h1>Purchasing</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Purchase Orders</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex flex-column mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">

            <!-- Botonera -->
            <x-permission-users :allowed-roles="['Thomas Admin']">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Nouvelle commande (New Order)">
                    <i class="bi bi-file-earmark-plus"></i> 
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Modifier commande (Edit Order)">
                    <i class="bi bi-pencil-square"></i> 
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Dupliquer commande (Duplicate)">
                    <i class="bi bi-files"></i> 
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Actualiser la liste (Refresh)">
                    <i class="bi bi-arrow-clockwise"></i> 
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Consulter les suivis (Check Follow-Ups)">
                    <i class="bi bi-search"></i> 
                </button>
            </div>
            </x-permission-users>

            <h4 class="mb-0 ms-3">List of unbilled purchase orders</h4>


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
        </div>
    </div>


<div id="gridFollowUp"></div>
@endsection
@push('scripts')
<script src="/assets/jqwidgets/jqxgrid.columnsreorder.js"></script>
<script>
    $(document).ready(function () {
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
    });
</script>
@endpush


