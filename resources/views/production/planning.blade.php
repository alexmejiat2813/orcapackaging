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
        $(document).ready(function () {
            const appointments = [
                {
                    id: "cmd1",
                    description: "Uteco: cmd 3313 - VIBAC",
                    subject: "cmd 3313 - VIBAC",
                    calendar: "Uteco",
                    start: new Date(2025, 3, 21, 0, 0),
                    end: new Date(2025, 3, 21, 23, 59)
                },
                {
                    id: "cmd2",
                    description: "Conversion 1: cmd 3213 - Protek",
                    subject: "cmd 3213 - Protek",
                    calendar: "Conversion 1",
                    start: new Date(2025, 3, 22, 0, 0),
                    end: new Date(2025, 3, 22, 23, 59)
                },
                {
                    id: "cmd3",
                    description: "Conversion 2: cmd 3252 - Oze Delice",
                    subject: "cmd 3252 - Oze Delice",
                    calendar: "Conversion 2",
                    start: new Date(2025, 3, 23, 0, 0),
                    end: new Date(2025, 3, 23, 23, 59)
                },
                {
                    id: "cmd4",
                    description: "Slitter: cmd Slitter Test",
                    subject: "cmd Slitter Test",
                    calendar: "Slitter",
                    start: new Date(2025, 3, 24, 0, 0),
                    end: new Date(2025, 3, 24, 23, 59)
                }
            ];

            const source = {
                dataType: "array",
                dataFields: [
                    { name: 'id', type: 'string' },
                    { name: 'description', type: 'string' },
                    { name: 'subject', type: 'string' },
                    { name: 'calendar', type: 'string' },
                    { name: 'start', type: 'date' },
                    { name: 'end', type: 'date' }
                ],
                id: 'id',
                localData: appointments
            };

            const adapter = new $.jqx.dataAdapter(source);

            $('#scheduler').jqxScheduler({
                //date: new Date(2025, 3, 21), // ✅ Replaced $.jqx.date with native JS Date
                width: '100%',
                height: 630,
                source: adapter,
                view: 'timelineWeekView',
                dayNameFormat: "abbr",
                showLegend: true,
                resources: {
                    colorScheme: "scheme04",
                    dataField: "calendar",
                    orientation: "vertical",
                    source: new $.jqx.dataAdapter({
                        dataType: "array",
                        dataFields: [
                            { name: 'calendar', type: 'string' }
                        ],
                        localData: [
                            { calendar: 'Uteco' },
                            { calendar: 'Conversion 1' },
                            { calendar: 'Conversion 2' },
                            { calendar: 'Wicket' },
                            { calendar: 'Slitter' },
                            { calendar: 'Sérigraphie' },
                            { calendar: 'Siat (Tapes)' }
                        ]
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
                    { type: 'timelineDayView', appointmentHeight: 30 },
                    { type: 'timelineWeekView', appointmentHeight: 30 },
                    { type: 'timelineMonthView', appointmentHeight: 30 }
                ]
            });
        });
    </script>
@endpush

