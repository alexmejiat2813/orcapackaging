import { urlGetOrders, urlSyncSchedule } from './config.js';

class OrdersModule {
    constructor() {
        this.currentFilters = [];
        this.shouldApplyFilters = false;
        this.adapter = null;
        this.source = null;
        this.existingAppointments = new Set();
        this.initialize();
    }

    applySavedFilters() {
        try {
            const grid = $("#commandesGrid");

            // Esto previene aplicar filtros si los datos aún se están cargando
            /*const rows = grid.jqxGrid('getrows');
            if (!rows || rows.length === 0) {
                console.warn("Grid not ready: no data rows yet.");
                return;
            }*/

            //grid.jqxGrid('clearfilters');

            this.currentFilters.forEach(filter => {
                const filterGroup = new $.jqx.filter();
                const filterCondition = filterGroup.createfilter('booleanfilter', filter.value, 'equal');
                filterGroup.addfilter(1, filterCondition);
                grid.jqxGrid('addfilter', filter.field, filterGroup);
            });

            grid.jqxGrid('applyfilters');

        } catch (error) {
            console.error("applySavedFilters() failed:", error);
        }
    }






    initialize() {
        const self = this;
        
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
            url: urlGetOrders
        };

        this.adapter = new $.jqx.dataAdapter(this.source);

        // jqxGrid initialization
        $("#commandesGrid").jqxGrid({
            width: '100%',
            source: this.adapter,
            pagermode: "simple",
            pageable: true,
            autoheight: true,
            sortable: true,
            filterable: true,
            columnsresize: true,
            showfilterrow: true, selectionmode: 'singlecell', enablebrowserselection: true,

            pageSize: 15,
            editable: true, 
            showtoolbar: false, groupable: false, showgroupsheader: false, showstatusbar: true,
            statusbarheight: 50,



            columns: [
                //{ text: 'Scheduled Date', datafield: 'Scheduled_Date', width: 110, columntype: 'datetimeinput', cellsformat: 'yyyy-MM-dd', align: 'center', cellsalign: 'center', editable: isAdmin },
                { text: 'ID', datafield: 'Commande_Id', align: 'center', cellsalign: 'center', width: 60, hidden:true },
                { text: '# Customer', datafield: 'Customer_Code', width: 95, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Customer', datafield: 'Customer_Name', width: 255, align: 'center', editable: false },
                {
                    text: '# Order', datafield: 'InInvoiceNumber', width: 75, align: 'center', cellsalign: 'center', editable: false, aggregates: ['count',
                        {
                            'Cappuccino Items':
                                function (aggregatedValue, currentValue) {
                                    if (currentValue == "Cappuccino") {
                                        return aggregatedValue + 1;
                                    }
                                    return aggregatedValue;
                                }
                        }
                    ] },
                { text: 'Client PO', datafield: 'Po_Client', width: 160, align: 'center', editable: false },
                { text: 'Order Date', datafield: 'Date_Commande', width: 110, cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', align: 'center', cellsalign: 'center', filtertype: 'range', editable: false },
                { text: 'Requested Date', datafield: 'Date_Demander', width: 110, cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', align: 'center', cellsalign: 'center', filtertype: 'range', editable: false },
                { text: 'Lot ID', datafield: 'Lot_Id', width: 60, align: 'center', cellsalign: 'center', editable: false },
                { text: '# Product', datafield: 'PrNumber', width: 180, align: 'center', editable: false },
                { text: 'Product', datafield: 'PrDescription1', width: 500, align: 'center', editable: false },
                { text: 'Order Qty', datafield: 'Lots_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Stock Qty', datafield: 'Qty_InStock', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Shipping Qty', datafield: 'Shipping_Qty', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Finish Qty', datafield: 'Qty_Finish', width: 100, align: 'center', cellsalign: 'center', editable: false },               
                { text: 'Lot Price', datafield: 'Lots_Price', width: 100, cellsformat: 'c4', align: 'center', cellsalign: 'right', editable: false },
                { text: 'Unit Price', datafield: 'Unit_Price', width: 100, align: 'center', cellsalign: 'center', editable: false },
                { text: 'Sub-Total', datafield: 'SubTotal', width: 100, cellsformat: 'c2', align: 'center', cellsalign: 'right', editable: false },
                { text: 'Total', datafield: 'Total', width: 100, cellsformat: 'c2', align: 'center', cellsalign: 'right', editable: false },
                { text: "Commande_Transmit_First", datafield: "Commande_Transmit_First", width: '5%', hidden: true },
                { text: "Transmit", datafield: "Transmit", width: '5%', hidden: true },
                { text: "Credit Autorise", datafield: "Credit_Autorise", width: '5%', hidden: true },
                { text: "isReady Production", datafield: "isReady_Production", width: '5%', hidden: true },
                { text: "Complet", datafield: "IsCompletedLogic", width: '5%', hidden: true },
                { text: "Cancel", datafield: "IsCanceledLogic", width: '5%', hidden: true },
            ],
            ready: function () {
                self.currentFilters = [
                    { field: "Commande_Transmit_First", value: true },
                    { field: "Transmit", value: true },
                    { field: "Credit_Autorise", value: true },
                    { field: "isReady_Production", value: true },
                    { field: "IsCompletedLogic", value: false },
                    { field: "IsCanceledLogic", value: false }
                ];

                self.applySavedFilters();
                // Aplicar filtros por defecto al cargar el grid
                /*const filters = [
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
                    $("#commandesGrid").jqxGrid('addfilter', filter.field, filterGroup);
                });
                $("#commandesGrid").jqxGrid('applyfilters');*/
            }
        });

        let firstBinding = true;

        $("#commandesGrid").on('bindingcomplete', function () {
            if (!firstBinding) return;
            firstBinding = false;
            // Esperar un poco para asegurar que el grid tenga columnas visibles
            setTimeout(() => {
                self.currentFilters = [
                    { field: "Commande_Transmit_First", value: true },
                    { field: "Transmit", value: true },
                    { field: "Credit_Autorise", value: true },
                    { field: "isReady_Production", value: true },
                    { field: "IsCompletedLogic", value: false },
                    { field: "IsCanceledLogic", value: false }
                ];
                // 🔹 PASO 1: Sincronizar checkboxes visuales con los filtros guardados
                self.currentFilters.forEach(filter => {
                    const $checkbox = $(`.status-filter[data-field="${filter.field}"]`);
                    if ($checkbox.length > 0) {
                        $checkbox.prop("checked", filter.value);
                    }
                });
                if (this.shouldApplyFilters) {
                    this.shouldApplyFilters = false;
                    self.applySavedFilters(); // ✅ Ahora sí seguro
                }
            }, 200); // puedes ajustar este delay si es necesario
        });

        // Filtros dinámicos al cambiar los checkboxes
        $(".status-filter").on("change", function () {
            const field = $(this).data("field");
            const isChecked = $(this).is(":checked");

            // Actualizar filtro en la variable global
            const index = self.currentFilters.findIndex(f => f.field === field);
            if (index !== -1) {
                self.currentFilters[index].value = isChecked;
            } else {
                self.currentFilters.push({ field, value: isChecked });
            }

            self.applySavedFilters();
            /*const field = $(this).data("field");
            const isChecked = $(this).is(":checked");

            $("#commandesGrid").jqxGrid('removefilter', field);

            let filterGroup = new $.jqx.filter();
            let value = isChecked ? true : false;
            let filter = filterGroup.createfilter('booleanfilter', value, 'equal');
            filterGroup.addfilter(1, filter);
            $("#commandesGrid").jqxGrid('addfilter', field, filterGroup);

            $("#commandesGrid").jqxGrid('applyfilters');*/
        });

        $("#btnRefresh").on("click", function () {
            this.shouldApplyFilters = true;
            $("#commandesGrid").jqxGrid('updatebounddata');
            setTimeout(() => {
                self.applySavedFilters();
                // Sincronizar checkboxes si hiciste cambios manuales en otra vista
                self.currentFilters.forEach(filter => {
                    const $checkbox = $(`.status-filter[data-field="${filter.field}"]`);
                    if ($checkbox.length > 0) {
                        $checkbox.prop("checked", filter.value);
                    }
                });
            }, 1000);
        });

    }

}

// Initialize the module
const orders = new OrdersModule();
