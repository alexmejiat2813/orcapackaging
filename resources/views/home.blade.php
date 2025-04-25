@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
<div class="pagetitle">
    <h1>Welcome to Orca Packaging</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">

        {{-- Clock-In Form --}}
        <div class="col-lg-6 mx-auto mb-5">
            <div class="card p-4 shadow rounded-3">
                <h4 class="text-center mb-4">🕒 Employee Clock In / Out</h4>

                <div id="responseMessage" style="font-weight:bold;"></div>

                <form id="clockInForm">
                    @csrf

                    <div class="mb-3">
                        <label for="barcode" class="form-label">Scan Your Badge</label>
                        <input type="password" name="barcode" id="barcode" class="form-control" autofocus required>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Comment (optional)</label>
                        <textarea name="note" id="note" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">📍 Register Entry/Exit</button>
                </form>
            </div>
        </div>

        {{-- Clock-In Records --}}
        <div class="col-12">
            <div class="container d-flex justify-content-center mt-4">
                <h3 class="mb-3">Clock-In Records</h3>
            </div>
        </div>

        <div class="col-12">
            <div class="container d-flex justify-content-center mt-4">
                <div id="timeInputGrid"></div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
    const jotformListUrl = "{{ url('/hr/timeinput/data') }}";

    $(document).ready(function () {
        initializeGrids();
        setupEventHandlers();
        startAutoRefresh();
    });

    function initializeGrids() {
        reloadTimeInputGrid();
    }

    function reloadTimeInputGrid() {
        fetch(jotformListUrl)
            .then(res => res.json())
            .then(data => {
                const source = {
                    localdata: data,
                    datatype: "array",
                    datafields: [
                        { name: 'id', type: 'int' },
                        { name: 'user', type: 'string' },
                        { name: 'start_time', type: 'date' },
                        { name: 'end_time', type: 'date' },
                        { name: 'comment', type: 'string' },
                        { name: 'time_minutes', type: 'int' },
                        { name: 'time_hours', type: 'float' },
                        { name: 'approved', type: 'string' },
                        { name: 'weekly_hours', type: 'string' }
                    ]
                };

                const dataAdapter = new $.jqx.dataAdapter(source);

                $("#timeInputGrid").jqxGrid({
                    width: '100%',
                    source: dataAdapter,
                    pageable: true,
                    autoheight: true,
                    sortable: true,
                    filterable: true,
                    columnsresize: true,
                    columns: [
                        { text: 'User', datafield: 'user', width: '33.3%', align: 'center', cellsalign: 'center' },
                        { text: 'Start Time', datafield: 'start_time', width: '33.3%', cellsformat: 'yyyy-MM-dd HH:mm', align: 'center', cellsalign: 'center' },
                        { text: 'Weekly Hours', datafield: 'weekly_hours', width: '33.3%', align: 'center', cellsalign: 'center' }
                    ]
                });
            });
    }

    function setupEventHandlers() {
        // Intercept form submit event and send via AJAX
        $('#clockInForm').submit(function (e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const $repMsg = $('#responseMessage');

            $.ajax({
                url: '/hr/clock-process',
                type: 'POST',
                data: formData,

                success: function (response) {
                    if (response.success) {
                        $repMsg.html('<div class="alert alert-success">' + response.message + '</div>');
                    } else {
                        $repMsg.html('<div class="alert alert-info">' + response.message + '</div>');
                    }
                    reloadTimeInputGrid();
                },

                error: function (xhr, status, error) {
                    $repMsg.html('<div class="alert alert-error">' + xhr.responseText + '</div>');
                },

                complete: function () {
                    $('#clockInForm')[0].reset();
                    $('#barcode').focus();
                    clearStatusMessage();
                }
            });
        });
    }

    function startAutoRefresh() {
        setInterval(reloadTimeInputGrid, 10000);
    }

    function clearStatusMessage() {
        setTimeout(() => {
            $('#responseMessage').fadeOut(300, function () {
                $(this).html('').show();
            });
        }, 5000);
    }
</script>
@endpush
