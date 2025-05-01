@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')

    <div class="pagetitle">
        <h1>Production Planning</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/production/index">Production</a></li>
                <li class="breadcrumb-item active">Production Planning</li>
            </ol>
        </nav>
    </div>

    <section class="section">
  <div id="scheduler"></div>
</section>

@endsection

@push('scripts')

    <script src="/assets/jqwidgets/jqxcore.js"></script>
    <script src="/assets/jqwidgets/jqxbuttons.js"></script>
    <script src="/assets/jqwidgets/jqxscrollbar.js"></script>
    <script src="/assets/jqwidgets/jqxdata.js"></script>
    <script src="/assets/jqwidgets/jqxdate.js"></script>
    <script src="/assets/jqwidgets/jqxscheduler.js"></script>
    <script src="/assets/jqwidgets/jqxscheduler.api.js"></script>
    <script src="/assets/jqwidgets/jqxdatetimeinput.js"></script>
    <script src="/assets/jqwidgets/jqxmenu.js"></script>
    <script src="/assets/jqwidgets/jqxcalendar.js"></script>
    <script src="/assets/jqwidgets/jqxtooltip.js"></script>
    <script src="/assets/jqwidgets/jqxwindow.js"></script>
    <script src="/assets/jqwidgets/jqxcheckbox.js"></script>
    <script src="/assets/jqwidgets/jqxlistbox.js"></script>
    <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
    <script src="/assets/jqwidgets/jqxnumberinput.js"></script>
    <script src="/assets/jqwidgets/jqxradiobutton.js"></script>
    <script src="/assets/jqwidgets/jqxinput.js"></script>


<script type="text/javascript">
let adapter;
let source;

        $(document).ready(function () {
            
            source = {
                dataType: "json",
                dataFields: [
                    { name: 'id', type: 'string' },
                    { name: 'description', type: 'string' },
                    { name: 'subject', type: 'string' },
                    { name: 'calendar', type: 'string' },
                    { name: 'start', type: 'date' },
                    { name: 'end', type: 'date' }
                ],
                id: 'id',
                url: "{{ url('/production/planning/get-appointments') }}"
                //localData: appointments
            };

            //const adapter = new $.jqx.dataAdapter(source);

             adapter = new $.jqx.dataAdapter(source, {
    beforeLoadComplete: function (records) {
        return records.map(function (rec) {
            rec.start = new Date(rec.start + 'Z'); // Forzar UTC
            rec.end = new Date(rec.end + 'Z');
            return rec;
        });
    }
});
    
 
            $('#scheduler').jqxScheduler({
                date: new $.jqx.date(new Date()), // ✅ Replaced $.jqx.date with native JS Date
                width: '100%',
                height: 700,
                source: adapter,
                view: 'timelineWeekView',
                dayNameFormat: "abbr",
                showLegend: true,
                 localization: { firstDay: 1},




                resources: {
    colorScheme: "scheme05",
    dataField: "calendar",
    
    source: new $.jqx.dataAdapter({
        dataType: "array",
        dataFields: [
            { name: 'calendar', type: 'string' },  // ID real
        ],
        localData: @json($machines) // Dinámico desde el backend
    })
},
                appointmentDataFields: {
                    from: 'start',
                    to: 'end',
                    id: 'id',
                    description: 'description',
                    subject: 'subject',
                    resourceId: 'calendar'
                },

                views: [
                    //{ type: "dayView", showWeekends: true },
                    //{ type: "weekView", showWeekends: true }
                    { type: "timelineDayView", text : "Day", showWeekends: true, timeSlotWidth: 64,showWorkTime: true,workTime:
                    {
                        fromDayOfWeek: 1,
                        toDayOfWeek: 5,
                        fromHour: 7,
                        toHour: 19
                    }, timeRuler: { formatString: "HH:mm", scale : "hour" } },
                    { type: "timelineWeekView", text : "Week", showWeekends: true, timeSlotWidth: 50,showWorkTime: true, workTime:
                    {
                        fromDayOfWeek: 1,
                        toDayOfWeek: 5,
                        fromHour: 7,
                        toHour: 19
                    },  timeRuler: { formatString: "HH:mm", scale : "hour" } },
                    { type: 'monthView', text : "Month", showWeekNumbers: true },
                    //{ type: "timelineMonthView", showWeekends: true, timeRuler: { formatString: "dd" } },
                    'agendaView'
        
    ]
            });




        });

        function saveAppointment(appointment) {
            

    const payload = {
        id: appointment.id,
        subject: appointment.subject,
        description: appointment.description,
        calendar: appointment.resourceId,
        start: appointment.from.toDate().toISOString(), // ✅ ISO sin duplicar timezone
        end: appointment.to.toDate().toISOString()
    };

    $.ajax({
        url: "/production/planning/save-appointment",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": '{{ csrf_token() }}'
        },
        data: payload,
        success: function (response) {
            console.log("Saved", response);
            // ✅ Refresca el source
    adapter.dataBind();


             // ✅ Toast simple
            alert("Appointment saved successfully!");
        },
        error: function (err) {
            console.error("Error in saveAppointment", err);
        }
    });
}


function deleteAppointment(id) {
    $.ajax({
        url: "/production/planning/delete-appointment",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": '{{ csrf_token() }}'
        },
        data: { id: id },
        success: function (response) {
            console.log("Deleted", response);
            adapter.dataBind();

            refreshAppointments();
        }
    });
}

       // 🔁 Agrega esto después de crear el scheduler:
$('#scheduler').on('appointmentAdd', function (event) {
    const appointment = event.args.appointment;
    saveAppointment(appointment); // INSERT
});

$('#scheduler').on('appointmentChange', function (event) {
    const appointment = event.args.appointment;
    saveAppointment(appointment); // UPDATE
});

$('#scheduler').on('appointmentDelete', function (event) {
    const appointment = event.args.appointment;
    deleteAppointment(appointment.id); // DELETE
});

    </script>
@endpush

