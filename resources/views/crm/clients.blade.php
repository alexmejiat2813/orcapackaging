@extends('layouts.app')

@section('title', 'Clients')

@section('content')

<div class="pagetitle">
    <h1>CRM</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Clients</li>
        </ol>
    </nav>
</div>

<div id="clientsSection">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 ms-1">Clients</h4>
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="filterActive" checked>
                <label class="form-check-label" for="filterActive">Actifs seulement</label>
            </div>
            <button type="button" id="btnRefresh" class="btn btn-outline-secondary btn-sm" title="Actualiser">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>

    <div id="clientsGrid"></div>
</div>

@endsection

@push('scripts')
<script src="/assets/jqwidgets/jqxgrid.columnsreorder.js"></script>
<script>
$(document).ready(function () {

    const source = {
        datatype: 'json',
        url: '/crm/clients/data',
        datafields: [
            { name: 'Customer_ID',        type: 'int' },
            { name: 'Customer_No',        type: 'string' },
            { name: 'Customer_Name',      type: 'string' },
            { name: 'Rep_Name',           type: 'string' },
            { name: 'Cst_Active',         type: 'bool' },
            { name: 'CuCity',             type: 'string' },
            { name: 'CuProvince',         type: 'string' },
            { name: 'CuISOCountryCode',   type: 'string' },
            { name: 'CuPostalCode',       type: 'string' },
            { name: 'CuPhoneNumber1',     type: 'string' },
            { name: 'CuEMail',            type: 'string' },
            { name: 'CuWebAddress',       type: 'string' },
            { name: 'CuTotalPurchases',   type: 'number' },
            { name: 'CuLastPurchasesDate',type: 'date' },
        ]
    };

    const dataAdapter = new $.jqx.dataAdapter(source);

    $("#clientsGrid").jqxGrid({
        width: '100%',
        height: 650,
        source: dataAdapter,
        sortable: true,
        filterable: true,
        showfilterrow: true,
        columnsresize: true,
        columnsreorder: true,
        altrows: true,
        selectionmode: 'singlerow',
        columns: [
            {
                text: '', datafield: 'Customer_ID', width: 50, align: 'center', cellsalign: 'center', sortable: false, filterable: false,
                cellsrenderer: function (row, col, val) {
                    return '<div style="margin:4px auto;text-align:center"><a href="/crm/clients/' + val + '" class="btn btn-outline-primary btn-sm py-0 px-1"><i class="bi bi-eye"></i></a></div>';
                }
            },
            { text: '# Client',     datafield: 'Customer_No',       width: '7%',  align: 'center', cellsalign: 'center' },
            { text: 'Nom',          datafield: 'Customer_Name',      width: '22%' },
            { text: 'Représentant', datafield: 'Rep_Name',           width: '10%', align: 'center', cellsalign: 'center' },
            { text: 'Ville',        datafield: 'CuCity',             width: '10%' },
            { text: 'Prov.',        datafield: 'CuProvince',         width: '5%',  align: 'center', cellsalign: 'center' },
            { text: 'Pays',         datafield: 'CuISOCountryCode',   width: '5%',  align: 'center', cellsalign: 'center' },
            { text: 'Téléphone',    datafield: 'CuPhoneNumber1',     width: '10%', align: 'center', cellsalign: 'center' },
            { text: 'Courriel',     datafield: 'CuEMail',            width: '15%' },
            { text: 'Total achats', datafield: 'CuTotalPurchases',   width: '9%',  cellsformat: 'c2', align: 'right', cellsalign: 'right' },
            { text: 'Dernier achat',datafield: 'CuLastPurchasesDate',width: '9%',  cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', filtertype: 'range' },
            { text: 'Actif',        datafield: 'Cst_Active',         width: '5%',  align: 'center', cellsalign: 'center',
                cellsrenderer: function (row, col, val) {
                    const badge = val ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>';
                    return '<div style="margin:5px auto;text-align:center">' + badge + '</div>';
                }
            },
        ],
        ready: function () {
            applyActiveFilter();
        }
    });

    function applyActiveFilter() {
        const showActiveOnly = $('#filterActive').is(':checked');
        if (showActiveOnly) {
            let fg = new $.jqx.filter();
            let f = fg.createfilter('booleanfilter', true, 'equal');
            fg.addfilter(1, f);
            $('#clientsGrid').jqxGrid('addfilter', 'Cst_Active', fg);
        } else {
            $('#clientsGrid').jqxGrid('removefilter', 'Cst_Active');
        }
        $('#clientsGrid').jqxGrid('applyfilters');
    }

    $('#filterActive').on('change', function () {
        applyActiveFilter();
    });

    $('#btnRefresh').on('click', function () {
        $('#clientsGrid').jqxGrid('updatebounddata', 'cells');
    });

    // Double-click row → navigate to detail
    $('#clientsGrid').on('rowdoubleclick', function (event) {
        const rowData = $('#clientsGrid').jqxGrid('getrowdata', event.args.rowindex);
        if (rowData && rowData.Customer_ID) {
            window.location.href = '/crm/clients/' + rowData.Customer_ID;
        }
    });
});
</script>
@endpush
