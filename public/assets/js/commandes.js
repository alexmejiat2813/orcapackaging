import { urlGetOrders, urlSyncSchedule } from './config.js';

class OrdersModule {
    constructor() {
        this.adapter = null;
        this.source = null;
        this.existingAppointments = new Set();
        this.initialize();
    }

    initialize() {
        this.source = {
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
                { name: 'Transmit', type: 'string' },
                { name: 'isReady_Production', type: 'boolean' },
                { name: 'Lot_Id', type: 'int' },
                { name: 'Product_Id', type: 'int' },
                { name: 'PrNumber', type: 'string' },
                { name: 'PrDescription1', type: 'string' },
                { name: 'Lots_Qty', type: 'int' },
                { name: 'Lots_Price', type: 'float' },
                { name: 'Shipping_Qty', type: 'float' },
                { name: 'Commentaire', type: 'string' },
                { name: 'Lots_Complet', type: 'string' },
                { name: 'SubTotal', type: 'float' },
                { name: 'Total', type: 'float' },
                { name: 'Qty_Finish', type: 'float' },
            ],
            id: 'Commande_Id',
            url: urlGetOrders
        };

        this.adapter = new $.jqx.dataAdapter(this.source);

        // jqxGrid initialization
        $("#commandesGrid").jqxGrid({
            width: '100%',
            source: this.adapter,
            pageable: true,
            autoheight: true,
            sortable: true,
            filterable: true,
            columnsresize: true,
            showfilterrow: true,
            pageSize: 17,
            editable: true,
            showtoolbar: true,
            rendertoolbar: function (toolbar) {
                const container = $("<div style='margin: 5px;'></div>");
                toolbar.append(container);
                container.append('<input class="btn btn-primary" id="syncButton" type="button" value="Synchronize Schedule" />');
                $("#syncButton").jqxButton();

                $('#syncButton').on('click', function () {
                    // Remove focus from edited cell to ensure value is saved
                    $('#commandesGrid').jqxGrid('endcelledit', $('#commandesGrid').jqxGrid('getselectedrowindex'), "Scheduled_Date", false);

                    const rows = $('#commandesGrid').jqxGrid('getrows');

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
                            $('#commandesGrid').jqxGrid('updatebounddata');
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

            columns: [
                { text: 'Scheduled Date', datafield: 'Scheduled_Date', width: 110, columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: isAdmin },
                { text: 'ID', datafield: 'Commande_Id', align: 'center', cellsalign: 'center', width: 60 },
                { text: '# Customer', datafield: 'Customer_Code', width: 95, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Customer', datafield: 'Customer_Name', width: 255, align: 'center', editable: false },
                { text: '# Order', datafield: 'InInvoiceNumber', width: 75, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Client PO', datafield: 'Po_Client', width: 160, align: 'center', editable: false },
                { text: 'Order Date', datafield: 'Date_Commande', width: 110, cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', align: 'center', cellsalign: 'center', editable: false },
                { text: 'Requested Date', datafield: 'Date_Demander', width: 110, cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', align: 'center', cellsalign: 'center', editable: false },
                { text: 'Lot ID', datafield: 'Lot_Id', width: 60, align: 'center', cellsalign: 'center', editable: false },
                { text: '# Product', datafield: 'PrNumber', width: 180, align: 'center', editable: false },
                { text: 'Product', datafield: 'PrDescription1', width: 500, align: 'center', editable: false },
                { text: 'Order Quantity', datafield: 'Lots_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Shipping Quantity', datafield: 'Shipping_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Finish Quantity', datafield: 'Qty_Finish', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Sub-Total', datafield: 'SubTotal', width: 100, cellsformat: 'c2', align: 'center', cellsalign: 'right', editable: false },
                { text: 'Total', datafield: 'Total', width: 100, cellsformat: 'c2', align: 'center', cellsalign: 'right', editable: false },
            ]
        });

    }

}

// Initialize the module
const orders = new OrdersModule();
