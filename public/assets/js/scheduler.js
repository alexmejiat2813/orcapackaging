import { urlGetAppointments, urlSaveAppointments, urlDeleteAppointments } from './config.js';

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
                records.forEach(rec => this.existingAppointments.add(rec.id));
                return records.map(rec => {
                    rec.start = new Date(rec.start + 'Z');
                    rec.end = new Date(rec.end + 'Z');
                    return rec;
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
            editDialog: false, // ❌ Desactiva completamente el popup de creación/edición

            resources: {
                colorScheme: "scheme17",
                dataField: "calendar",
                //orientation: "horizontal",
                source: new $.jqx.dataAdapter({
                    dataType: "array",
                    dataFields: [
                        { name: 'calendar', type: 'string' }
                    ],
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
                    workTime: {
                        fromDayOfWeek: 1,
                        toDayOfWeek: 5,
                        fromHour: 7,
                        toHour: 20
                    },
                    timeSlotWidth: '64.8',
                    timeRuler: { formatString: "HH:mm", scale: "hour" }
                },
                {                    
                    type: "timelineWeekView",
                    text: "Week",
                    showWeekends: true,
                    showWorkTime: true,
                    workTime: {
                        fromDayOfWeek: 1,
                        toDayOfWeek: 5,
                        fromHour: 7,
                        toHour: 20
                    },
                    timeSlotWidth: 20,
                    timeRuler: { formatString: "HH", scale: "hour" }
                },
                { type: 'monthView', text: "Month", showWeekNumbers: true },
                'agendaView'
            ]
        });

        //$('#scheduler').on('appointmentAdd', (event) => this.saveAppointment(event.args.appointment));
        $('#scheduler').on('appointmentChange', (event) => this.saveAppointment(event.args.appointment));
        //$('#scheduler').on('appointmentDelete', (event) => this.deleteAppointment(event.args.appointment.id));
        $('#scheduler').on('appointmentAdd', function (event) {
            event.preventDefault(); // ❌ Cancela la creación
            Swal.fire('Acción bloqueada', 'No se permite crear nuevas citas.', 'warning');
        });

    }

    refreshScheduler() {
        // Save current view and date before refresh
        const currentView = $('#scheduler').jqxScheduler('view');
        const currentDate = $('#scheduler').jqxScheduler('date');

        const newAdapter = new $.jqx.dataAdapter(this.source, {
            beforeLoadComplete: (records) => {
                this.existingAppointments.clear();
                records.forEach(rec => this.existingAppointments.add(rec.id));
                return records.map(rec => {
                    rec.start = new Date(rec.start + 'Z');
                    rec.end = new Date(rec.end + 'Z');
                    return rec;
                });
            }
        });

        this.adapter = newAdapter;

        // Set new data source and restore view + date
        $('#scheduler').jqxScheduler('source', this.adapter);
        //$('#scheduler').jqxScheduler('render');
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
            url: url,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": window.csrfToken
            },
            data: data,
            success: (response) => {
                this.refreshScheduler();
                //Swal.fire(titleMessage, successMessage, 'success');
            },
            error: (err) => {
                const messageBox = document.getElementById('messageBox');
                if (messageBox) {
                    messageBox.innerHTML = `<div class='alert alert-danger'>${err.responseText || 'Unexpected error occurred.'}</div>`;
                }
                Swal.fire('Error!', 'An error occurred while processing the request.', 'error');
            }
        });
    }

    saveAppointment(appointment) {
        const payload = this.buildAppointmentPayload(appointment);

        // Prevent duplicate insert if ID already exists
        if (!this.existingAppointments.has(payload.id)) {
            this.handleAjax(urlSaveAppointments, payload, "Saved!", "Appointment created successfully!");
        } else {
            this.handleAjax(urlSaveAppointments, payload, "Updated!", "Appointment updated successfully!");
        }
    }

    deleteAppointment(id) {
        this.handleAjax(urlDeleteAppointments, { id: id }, "Deleted!", "Appointment deleted successfully!");
    }
}

// Initialize the module
const scheduler = new SchedulerModule();
