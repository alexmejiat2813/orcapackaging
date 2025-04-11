$(document).ready(function () {
    initializePopupWindow();
    initializeGrids();
    setupEventHandlers();
    startAutoRefresh();
});

// ----------------------------------
// Initialize jqxWindow
// ----------------------------------
function initializePopupWindow() {
    $("#popupForm").jqxWindow({
        width: '95%',
        maxWidth: 800,
        height: 'auto',
        resizable: true,
        isModal: true,
        autoOpen: false,
        modalOpacity: 0.25,
        animationType: 'fade',
        title: "New Supply Request"
    });

    $('#openForm').on('click', function () {
        $('#popupForm').jqxWindow('open');
    });

    $('#popupForm').on('open', function () {
        $('body').addClass('modal-open');
    });

    $('#popupForm').on('close', function () {
        $('body').removeClass('modal-open');
    });
}

// ----------------------------------
// Initialize Data Grids
// ----------------------------------
function initializeGrids() {
    loadActivePurchasesGrid();
    reloadJotformGrid();
}

// Purchase Orders Grid
function loadActivePurchasesGrid() {
    const purchases = window.activePurchases || [];

    const source = {
        localdata: purchases,
        datatype: "array"
    };

    const dataAdapter = new $.jqx.dataAdapter(source);

    $("#grid").jqxGrid({
        width: '100%',
        source: dataAdapter,
        pageable: true,
        autoheight: true,
        sortable: true,
        filterable: true,
        showfilterrow: true,
        columnsresize: true,
        selectionmode: 'singlerow',
        columns: [
            { text: 'Order ID', datafield: 'PO_No', width: 80, cellsalign: 'center' },
            { text: 'Supplier', datafield: 'Supplier_Name', width: 320 },
            { text: 'Product Description', datafield: 'PrDescription1', width: 450 },
            { text: 'Order Quantity', datafield: 'Order_Quantity', width: 100 },
            { text: 'Receive Quantity', datafield: 'PO_Detail_QtyReceive', width: 100 },
            { text: 'Comment', datafield: 'PO_Detail_Comment', width: 350 },
            { text: 'Status', datafield: 'status_text', width: 80, cellsalign: 'center' },
            {
                text: 'Date',
                datafield: 'PO_Date',
                width: 100,
                cellsalign: 'center',
                cellsformat: 'yyyy-MM-dd',
                columntype: 'datetimeinput',
                filtertype: 'date'
            }
        ]
    });
}

// JotForm Grid
function reloadJotformGrid() {
    fetch('/jotform/jotformSupplies/list')
        .then(res => res.json())
        .then(data => {
            const source = {
                localdata: data,
                datatype: "array"
            };
            const adapter = new $.jqx.dataAdapter(source);

            $("#gridJotform").jqxGrid({
                width: '100%',
                source: adapter,
                pageable: true,
                autoheight: true,
                sortable: true,
                filterable: true,
                showfilterrow: true,
                columnsresize: true,
                selectionmode: 'singlerow',
                columns: [
                    { text: 'Date', datafield: 'created_at', width: 130 },
                    { text: 'Machine', datafield: 'machine', width: 150 },
                    { text: 'Description', datafield: 'description', width: 200 },
                    { text: 'Urgency', datafield: 'urgency', width: 150 },
                    { text: 'Qty in Stock', datafield: 'stock_quantity', width: 100, cellsalign: 'center' },
                    { text: 'Notes', datafield: 'notes', width: 300 },
                    {
                        text: 'Managed',
                        datafield: 'managed',
                        width: 80,
                        cellsalign: 'center',
                        columntype: 'checkbox',
                        editable: true
                    }
                ]
            });
        });
}

// ----------------------------------
// Button handlers and refresh logic
// ----------------------------------
function setupEventHandlers() {
    $('#syncJotformBtn').on('click', function () {
        const $btn = $(this);
        const $status = $('#syncStatus');

        $btn.prop('disabled', true);
        $status.html('🔄 Syncing with JotForm...').show();

        $.ajax({
            url: '/jotform/jotformSupplies/importAllSubmissions',
            type: 'GET',
            success: function () {
                $status.html('✅ Synced successfully!');
                reloadJotformGrid();
            },
            error: function (xhr) {
                $status.html('❌ Sync error: ' + xhr.responseText);
            },
            complete: function () {
                $btn.prop('disabled', false);
                clearStatusMessage();
            }
        });
    });
}

// Refresh every 10 seconds
function startAutoRefresh() {
    setInterval(reloadJotformGrid, 10000);
}

// Clear sync status message
function clearStatusMessage() {
    setTimeout(() => {
        $('#syncStatus').fadeOut(300, function () {
            $(this).html('').show();
        });
    }, 5000);
}
