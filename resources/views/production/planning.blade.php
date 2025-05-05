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
        <div id="messageBox"></div>
    </section>

@endsection

@push('scripts')
    <!-- Include jqx scripts -->
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

    <!-- SweetAlert for alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global machine data -->
    <script>const machineData = @json($machines);</script>

    <!-- Import JS modules -->
    <script type="module" src="/assets/js/config.js"></script>
    <script type="module" src="/assets/js/scheduler.js"></script>
@endpush

