$(document).ready(function () {
    initializePopupWindow();
    initializeGrids();
    setupEventHandlers();
    startAutoRefresh();
    setupGridEditing();
    clearStatusLater();
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
    //loadActivePurchasesGrid();
    reloadJotformGrid();
}

// Purchase Orders Grid
/*function loadActivePurchasesGrid() {
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
*/
// JotForm Grid
function reloadJotformGrid() {
    fetch(jotformListUrl)
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
                editable: true,
                selectionmode: 'singlerow',
                columns: [
                    /*{ text: 'Form ID', datafield: 'jotform_id', width: 200 },
                    {
                        text: 'Managed',
                        datafield: 'managed',
                        cellsalign: 'center',
                        width: 70,
                        columntype: 'checkbox',
                        editable: true,
                        cellclassname: function () {
                            return 'highlight-editable';
                        }
                    },*/
                    { text: 'Date', datafield: 'created_at', width: 130, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Machine', datafield: 'machine', width: 150, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Description', datafield: 'description', width: 200, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Urgency', datafield: 'urgency', width: 350, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Qty in Stock', datafield: 'stock_quantity', width: 100, align: 'center', cellsalign: 'center', editable: false },
                    { text: 'Notes', datafield: 'notes', width: 620, align: 'center', cellsalign: 'center', editable: false }
                ]
            });
        })
        .catch(() => {
            $('#syncStatus').html('❌ Failed to load data from server.');
            clearStatusLater();
        });
}

// ----------------------------------
// Editar "Managed" y enviar cambios
// ----------------------------------
function setupGridEditing() {
    $('#gridJotform').on('cellendedit', function (event) {
        const args = event.args;
        const datafield = args.datafield;
        const newValue = args.value;
        const rowIndex = args.rowindex;

        if (datafield === 'managed') {
            const row = $('#gridJotform').jqxGrid('getrowdata', rowIndex);

            alert(row.jotform_id);

            $.ajax({
                url: '/purchasing/jotform/jotformSupplies/updateManaged',
                method: 'POST', // ✅
                data: {
                    submission_id: row.jotform_id,
                    managed: newValue ? 1 : 0
                },
                headers: {
                    "X-CSRF-TOKEN": window.csrfToken
                },
                success: function () {
                    $('#syncStatus').html('✅ Synced successfully!').fadeIn();
                    clearStatusLater();
                },
                error: function (xhr) {
                    $('#syncStatus').html('❌ Sync error:' + xhr.responseText).fadeIn();
                    clearStatusLater();
                }
            });

           
            return false; // 👈 para evitar bubbling innecesario

        }

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
            url: '/purchasing/jotform/jotformSupplies/importAllSubmissions',
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

// ----------------------------------
// Detección de envío desde el iframe
// ----------------------------------
function checkJotformSubmit() {
    const iframe = document.getElementById('JotFormIFrame-250704767667064');
    if (iframe && iframe.contentWindow && iframe.contentWindow.location.href.includes("thankyou")) {
        $('#popupForm').jqxWindow('close');
        $('#syncStatus').html('📨 Form submitted! Syncing...');
        setTimeout(() => {
            reloadJotformGrid();
            clearStatusLater();
        }, 1000);
    }
}

function clearStatusLater() {
    setTimeout(() => {
        $('#syncStatus').fadeOut(300, function () {
            $(this).html('').show();
        });
    }, 5000);
}
