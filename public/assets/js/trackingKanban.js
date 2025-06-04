// trackingKanban.js

export class TrackingKanban {
    constructor(kanbanId, filterInputSelectors, endpointUrl) {
        this.kanbanId = kanbanId;
        this.filterInputSelectors = filterInputSelectors;
        this.endpointUrl = endpointUrl;
        this.originalData = [];

        this.initialize();
    }

    initialize() {
        this.loadData();

        const filterInputs = document.querySelectorAll(this.filterInputSelectors);
        filterInputs.forEach(input => {
            input.addEventListener('input', () => this.collectAndApplyFilters());
        });

        /*document.getElementById(this.filterInputId).addEventListener('input', (e) => {
            const query = e.target.value.trim().toLowerCase();
            this.applyFilter(query);
        });*/
    }

    loadData() {
        fetch(this.endpointUrl)
            .then(response => response.json())
            .then(json => {
                this.originalData = json.map((item, index) => ({
                    id: `${item.Lot_Id}`,
                    kanban_status: this.mapStatus(item.KANBAN_STATUS),
                    label: `<strong>Cmd :</strong> ${item.InInvoiceNumber} <br> <strong>Client :</strong> (${item.Customer_Code}) ${item.Customer_Name} <br> <strong>Lot :</strong> ${item.Lot_Id} <br> ${item.PrNumber} <br> ${item.PrDescription1.substring(0, 100)} ${item.PrDescription2.substring(0, 100)} ${item.PrDescription3.substring(0, 100)} <br> <strong>Qty Order :</strong> ${parseInt(item.Lots_Qty) || 0} <br> <strong>In Stock :</strong> ${parseInt(item.Qty_InStock) || 0} <br> <strong>Shipped :</strong> ${parseInt(item.Total_Shipped) || 0}`,
                    tags: `Cli ${item.Customer_Code}, Cmd ${item.InInvoiceNumber}`,
                    color: this.getColor(item.KANBAN_STATUS),
                    invoice: item.InInvoiceNumber
                }));
                this.renderKanban(this.originalData);
            })
            .catch(error => {
                console.error('Error loading Kanban data:', error);
                alert('Unable to load kanban data.');
            });
    }

    /*applyFilter(query) {
        if (!query) {
            this.renderKanban(this.originalData);
            return;
        }

        const filtered = this.originalData.filter(item =>
            item.invoice && item.invoice.toLowerCase().includes(query)
        );
        this.renderKanban(filtered);
    }*/

    applyFilter(filters) {
        const filtered = this.originalData.filter(item => {
            const codeclientMatch = !filters.codeclient || (item.label && item.label.toLowerCase().includes(filters.codeclient));
            const clientMatch = !filters.client || (item.label && item.label.toLowerCase().includes(filters.client));
            const orderMatch = !filters.order || (item.label && item.label.toLowerCase().includes(filters.order));
            const lotMatch = !filters.lot || (item.label && item.label.toLowerCase().includes(filters.lot));
            const productMatch = !filters.product || (item.label && item.label.toLowerCase().includes(filters.product));
            // Agrega más condiciones si tienes más filtros

            return codeclientMatch && clientMatch && orderMatch && lotMatch && productMatch;
        });

        this.renderKanban(filtered);
    }

    collectAndApplyFilters() {
        const filters = {};
        document.querySelectorAll(this.filterInputSelectors).forEach(input => {
            filters[input.name] = input.value.trim().toLowerCase();
        });

        this.applyFilter(filters);
    }

    renderKanban(data) {
        const source = {
            localData: data,
            dataType: "array",
            dataFields: [
                { name: "id", type: "string" },
                { name: "status", map: "kanban_status", type: "string" },
                { name: "text", map: "label", type: "string" },
                { name: "tags", type: "string" },
                { name: "color", type: "string" },
                { name: "invoice", type: "string" }
            ]
        };

        const dataAdapter = new $.jqx.dataAdapter(source);

        $(`#${this.kanbanId}`).jqxKanban({
            resources: [],
            source: dataAdapter,
            width: '100%',
            height: 625,
            columns: [
                { text: "Backlog", dataField: "new" },
                { text: "In Progress", dataField: "work" },
                { text: "Stock", dataField: "stock" },
                { text: "Partial", dataField: "partial" },
                { text: "Done", dataField: "done" }
            ]
        });
    }

    mapStatus(status) {
        switch (status.toLowerCase()) {
            case 'backlog': return 'new';
            case 'in progress': return 'work';
            case 'stock': return 'stock';
            case 'partial': return 'partial';
            case 'done': return 'done';
            default: return 'new';
        }
    }

    getColor(status) {
        switch (status.toLowerCase()) {
            case 'backlog': return '#5dc3f0';
            case 'in progress': return '#f19b60';
            case 'stock': return '#17a2b8';
            case 'partial': return '#ffc107';
            case 'done': return '#6bbd49';
            default: return '#cccccc';
        }
    }
}

// USAGE EXAMPLE:
// new TrackingKanban('kanban', 'filterInput', '/tracking/kanban-data');
