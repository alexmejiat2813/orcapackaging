import { urlGetDataLiveOrders, urlSaveAppointments, urlDeleteAppointments } from './config.js';

class SchedulerModule {
    constructor() {
        this.adapter = null;
        this.source = null;
        this.existingAppointments = new Set();
        this.initialize();
    }

    initialize() {
        this.reloadSchedulerData(); // Llama al cargar la página

        // Cuando se cambia la vista (day/week/month)
        $('#scheduler').on('viewChange', () => this.reloadSchedulerData());

        // 🟢 Cuando se cambia la fecha con las flechas o el calendario
        $('#scheduler').on('dateChange', () => this.reloadSchedulerData());

        $('#scheduler').jqxScheduler({
            date: new $.jqx.date(new Date()),
            width: '100%',
            height: 700,
            source: this.adapter,
            view: 'timelineDayView',
            dayNameFormat: "abbr",
            showLegend: true,
            localization: { firstDay: 1 },
            contextMenu: false,
            editDialog: false,

            resources: {
                colorScheme: "scheme17",
                dataField: "calendar",
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
                        fromHour: 6,
                        toHour: 22
                    },
                    timeSlotWidth: '64.8',
                    timeRuler: { formatString: "HH:mm", scale: "quarterHour" }
                }/*,
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
                'agendaView'*/
            ]
        });

        $('#scheduler').on('appointmentChange', (event) => this.saveAppointment(event.args.appointment));
        $('#scheduler').on('appointmentAdd', function (event) {
            event.preventDefault();
            Swal.fire('Acción bloqueada', 'No se permite crear nuevas citas.', 'warning');
        });

        $('#scheduler').on('appointmentChange', function (event) {
            event.preventDefault();
            Swal.fire('Solo lectura', 'No puedes modificar esta cita.', 'info');
        });

        setInterval(() => {
            this.reloadSchedulerData();
            console.log("🔄 Live Orders actualizados automáticamente.");
        }, 15 * 60 * 1000); // 15 minutos
    }

    getSchedulerDate() {
        const schedulerDate = $("#scheduler").jqxScheduler('date');
        if (!schedulerDate || typeof schedulerDate.toDate !== 'function') {
            //console.warn("⚠️ No valid scheduler date found, using today's date.");
            return new Date().toLocaleDateString('en-CA'); // fallback a hoy
        }

        return schedulerDate.toDate().toLocaleDateString('en-CA');
    }

    reloadSchedulerData() {
        const selectedDate = this.getSchedulerDate();

        const source = {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'string' },
                { name: 'location', type: 'string' },
                { name: 'description', type: 'string' },
                { name: 'subject', type: 'string' },
                { name: 'calendar', type: 'string' },
                { name: 'start', type: 'date' },
                { name: 'end', type: 'date' }
            ],
            url: urlGetDataLiveOrders + '?date=' + selectedDate
        };

        this.adapter = new $.jqx.dataAdapter(source, {
            beforeLoadComplete: (records) => {
                this.existingAppointments.clear();

                const toLocalDate = (input) => {
                    if (input instanceof Date) return input;
                    if (typeof input === 'string') return new Date(input.replace(' ', 'T'));
                    return null;
                };

                return records.map(rec => {
                    rec.start = toLocalDate(rec.start);
                    rec.end = toLocalDate(rec.end);
                    this.existingAppointments.add(rec.id);
                    return rec;
                });
            }


        });

        $('#scheduler').jqxScheduler('source', this.adapter);
    }

    refreshScheduler() {
        const currentView = $('#scheduler').jqxScheduler('view');
        const currentDate = $('#scheduler').jqxScheduler('date');

        this.reloadSchedulerData();

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

// Inicializar módulo
const scheduler = new SchedulerModule();
