// trackingKanban.js

export class TrackingKanban {
    constructor(kanbanId, filterInputId, endpointUrl) {
        this.kanbanId = kanbanId;
        this.filterInputId = filterInputId;
        this.endpointUrl = endpointUrl;
        this.originalData = [];

        this.initialize();
    }

    initialize() {
        this.loadData();

        document.getElementById(this.filterInputId).addEventListener('input', (e) => {
            const query = e.target.value.trim().toLowerCase();
            this.applyFilter(query);
        });
    }

    loadData() {
        fetch(this.endpointUrl)
            .then(response => response.json())
            .then(json => {
                this.originalData = json.map((item, index) => ({
                    id: `${item.Lot_Id}`,
                    kanban_status: this.mapStatus(item.KANBAN_STATUS),
                    label: `Lot ${item.Lot_Id} - ${item.PrDescription1.substring(0, 100)} ${item.PrDescription2.substring(0, 100)}`,
                    tags: `${item.Customer_Code}, ${item.InInvoiceNumber}, ${item.PrNumber}`,
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

    applyFilter(query) {
        if (!query) {
            this.renderKanban(this.originalData);
            return;
        }

        const filtered = this.originalData.filter(item =>
            item.invoice && item.invoice.toLowerCase().includes(query)
        );
        this.renderKanban(filtered);
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
