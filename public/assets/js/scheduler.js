import { urlGetAppointments, urlSaveAppointments, urlDeleteAppointments, urlSyncSchedule } from './config.js';

class SchedulerModule {
    constructor() {
        this.adapter = null;
        this.source = null;
        this.existingAppointments = new Set();
        this.initialize();
    }

    initialize() {
        this.source = {
            dataType: "json",
            dataFields: [
                { name: 'id', type: 'string' },
                { name: 'location', type: 'string' },
                { name: 'description', type: 'string' },
                { name: 'subject', type: 'string' },
                { name: 'calendar', type: 'string' },
                { name: 'start', type: 'date' },
                { name: 'end', type: 'date' },
            ],
            id: 'id',
            url: urlGetAppointments
        };

        this.adapter = new $.jqx.dataAdapter(this.source, {
            beforeLoadComplete: (records) => {
                this.existingAppointments.clear();
                const toLocalDate = (input) => {
                    if (input instanceof Date) return input;
                    if (typeof input === 'string') return new Date(input.replace(' ', 'T'));
                    return null;
                };
                records.forEach(rec => this.existingAppointments.add(rec.id));
                return records.map(rec => {
                    rec.start = toLocalDate(rec.start);
                    rec.end = toLocalDate(rec.end);
                    return rec;
                });
            },
            loadError: (xhr, status, error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar a la base de datos.',
                    confirmButtonText: 'Reintentar'
                }).then(() => {
                    this.refreshScheduler();
                });
            }
        });

        $('#scheduler').jqxScheduler({
            date: new $.jqx.date(new Date()),
            width: '100%',
            height: 700,
            source: this.adapter,
            view: 'timelineWeekView',
            dayNameFormat: "abbr",
            showLegend: true,
            localization: { firstDay: 1 },
            contextMenu: false,
            editDialog: false,
            ready: function () {
                $("#scheduler").jqxScheduler('ensureVisible', new $.jqx.date(new Date().setHours(7, 0, 0, 0)));
            },
            resources: {
                colorScheme: "scheme17",
                dataField: "calendar",
                source: new $.jqx.dataAdapter({
                    dataType: "array",
                    dataFields: [{ name: 'calendar', type: 'string' }],
                    localData: machineData
                })
            },
            appointmentDataFields: {
                from: 'start',
                to: 'end',
                id: 'id',
                location: 'location',
                description: 'description',
                subject: 'subject',
                resourceId: 'calendar'
            },
            views: [
                {
                    type: "timelineDayView",
                    text: "Day",
                    showWeekends: true,
                    showWorkTime: true,
                    workTime: { fromDayOfWeek: 1, toDayOfWeek: 5, fromHour: 7, toHour: 20 },
                    timeSlotWidth: '64.8',
                    timeRuler: { formatString: "HH:mm", scale: "hour" }
                },
                {
                    type: "timelineWeekView",
                    text: "Week",
                    showWeekends: true,
                    showWorkTime: true,
                    workTime: { fromDayOfWeek: 1, toDayOfWeek: 5, fromHour: 7, toHour: 20 },
                    timeSlotWidth: 20,
                    timeRuler: { formatString: "HH", scale: "hour" }
                },
                { type: 'monthView', text: "Month", showWeekNumbers: true },
                'agendaView'
            ]
        });

        $('#scheduler').on('appointmentChange', (event) => this.saveAppointment(event.args.appointment));
        $('#scheduler').on('appointmentAdd', function (event) {
            event.preventDefault();
            Swal.fire('Acción bloqueada', 'No se permite crear nuevas citas.', 'warning');
        });
    }

    refreshScheduler() {
        const currentView = $('#scheduler').jqxScheduler('view');
        const currentDate = $('#scheduler').jqxScheduler('date');

        this.adapter = new $.jqx.dataAdapter(this.source, {
            beforeLoadComplete: (records) => {
                this.existingAppointments.clear();
                const toLocalDate = (input) => {
                    if (input instanceof Date) return input;
                    if (typeof input === 'string') return new Date(input.replace(' ', 'T'));
                    return null;
                };
                records.forEach(rec => this.existingAppointments.add(rec.id));
                return records.map(rec => {
                    rec.start = toLocalDate(rec.start);
                    rec.end = toLocalDate(rec.end);
                    return rec;
                });
            }
        });

        $('#scheduler').jqxScheduler('source', this.adapter);
        $('#scheduler').jqxScheduler('view', currentView);
        $('#scheduler').jqxScheduler('date', currentDate);
    }

    buildAppointmentPayload(appointment) {
        return {
            id: appointment.id,
            location: appointment.location,
            subject: appointment.subject,
            description: appointment.description,
            calendar: appointment.resourceId,
            start: appointment.from.toDate().toISOString(),
            end: appointment.to.toDate().toISOString()
        };
    }

    handleAjax(url, data, titleMessage = 'Message', successMessage = "Operation completed") {
        $.ajax({
            url,
            method: "POST",
            headers: { "X-CSRF-TOKEN": window.csrfToken },
            data,
            success: () => this.refreshScheduler(),
            error: (err) => {
                Swal.fire('Error!', 'An error occurred while processing the request.', 'error');
            }
        });
    }

    saveAppointment(appointment) {
        const payload = this.buildAppointmentPayload(appointment);
        const msg = this.existingAppointments.has(payload.id) ? "Updated!" : "Saved!";
        this.handleAjax(urlSaveAppointments, payload, msg, "Appointment saved!");
    }

    deleteAppointment(id) {
        this.handleAjax(urlDeleteAppointments, { id }, "Deleted!", "Appointment deleted!");
    }
}

$(document).ready(function () {
    const scheduler = new SchedulerModule();

    $('#abrirModal').on('click', function () {
        const modalId = 'openOrdersModal';
        const modalElement = document.getElementById(modalId);

        // 🔥 1. Elimina instancia previa del modal si existe
        const oldInstance = bootstrap.Modal.getInstance(modalElement);
        if (oldInstance) oldInstance.dispose();



        // ✅ 4. Recrea el grid o contenido (ejemplo: volver a crear el jqxGrid)
        inicializarTablaOrdenes(); // 👈 asegúrate de tener esta función para regenerar el grid

        // ✅ 5. Crear nueva instancia y mostrar el modal
        const newModal = new bootstrap.Modal(modalElement);
        newModal.show();
    });

    function inicializarTablaOrdenes() {


    const listSource = {
        datatype: "json",
        datafields: [
            { name: 'Scheduled_Date', type: 'date' },
            { name: 'Commande_Id', type: 'int' },
            { name: 'Customer_No', type: 'string' },
            { name: 'Customer_Name', type: 'string' },
            { name: 'InInvoiceNumber', type: 'string' },            
            { name: 'Lot_ID', type: 'int' },            
            { name: 'PrNumber', type: 'string' },
            { name: 'PrDescription1', type: 'string' },
            { name: 'Equipment_Id', type: 'int' }, // 👈 nuevo
            { name: 'Equipment_Description', type: 'string' }, // 👈 nuevo
            { name: 'qtyHeures', type: 'float' }, // 👈 nuevo
            
        ],
        id: 'Commande_Id',
        url: '/production/production/get-schedules'
    };

    let dataAdapter = new $.jqx.dataAdapter(listSource);

    $("#jqxTable").jqxGrid({
        width: '100%',
        sortable: true,
        filterable: true,
        editable: true,
        showfilterrow: true,
        source: dataAdapter,
        enabletooltips: true,
        contextmenuenabled: true,
        showgroupsheader: true,
        rendertoolbar: function (toolbar) {
            const container = $("<div style='margin: 5px;'></div>");
            toolbar.append(container);
            container.append('<input class="btn btn-primary" id="syncButton" type="button" value="Synchronize Schedule" />');
            $("#syncButton").jqxButton();

            $('#syncButton').on('click', function () {
                $('#jqxTable').jqxGrid('endcelledit', $('#jqxTable').jqxGrid('getselectedrowindex'), "Scheduled_Date", false);
                const rows = $('#jqxTable').jqxGrid('getrows');
                const selectedLots = rows
                    .filter(row => row.Scheduled_Date instanceof Date && !isNaN(row.Scheduled_Date))
                    .map(row => ({
                        lot_id: row.Lot_Id,
                        commande_id: row.Commande_Id,
                        Scheduled_Date: row.Scheduled_Date.toISOString().split('T')[0]
                    }));

                if (!selectedLots.length) {
                    Swal.fire('No data', 'No lots with a scheduled date to synchronize.', 'info');
                    return;
                }

                $("#syncButton").prop("disabled", true).val("Synchronizing...");
                Swal.fire({ title: 'Synchronizing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                fetch(urlSyncSchedule, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-TOKEN": window.csrfToken
                    },
                    body: JSON.stringify({ lots: selectedLots })
                })
                    .then(res => res.json())
                    .then(result => {
                        Swal.fire('Done', `Synchronization complete. Changes made: ${result.updated}`, 'success')
                            .then(() => {
                            
                                $('#jqxTable').jqxGrid('updatebounddata');
                                scheduler.refreshScheduler();

                                const modal = bootstrap.Modal.getInstance(document.getElementById('openOrdersModal'));
                                if (modal) modal.hide();
                            });

                        

                
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'An error occurred during synchronization.', 'error');
                    })
                    .finally(() => {
                        $("#syncButton").prop("disabled", false).val("Synchronize Schedule");
                    });
            });
        },
        columns: [
            { text: 'Customer Code', dataField: 'Customer_No', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Customer', dataField: 'Customer_Name', width: '20%', align: 'center', editable: false },
            { text: 'CMD', dataField: 'InInvoiceNumber', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Lot Id', dataField: 'Lot_ID', width: '5%', align: 'center', cellsalign: 'center', editable: false },
            { text: 'Product', dataField: 'PrDescription1', width: '70%', align: 'center', editable: false },
            { text: 'Equipment', dataField: 'Equipment_Description', width: '8%', align: 'center', editable: false },            
            { text: 'Scheduled Date', dataField: 'Scheduled_Date', cellsformat: 'yyyy-MM-dd HH:mm', columntype: 'datetimeinput', width: '10%', align: 'center', editable: true },
            { text: 'Estimated Hours', dataField: 'qtyHeures', width: '6%', align: 'center', cellsalign: 'center', editable: true },
        ]
    });
    }
});
