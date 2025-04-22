@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
<div class="pagetitle">
    <h1>Welcome Orca Packaging</h1>
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

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('hr.clock.process') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="barcode" class="form-label">Scan Your Badge</label>
                        <input type="text" name="barcode" id="barcode" class="form-control" autofocus required>
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
            <div class="container mt-4">
                <h3 class="mb-3">Clock-In Records</h3>
                <div id="timeInputGrid"></div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var source = {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int' },
                { name: 'user', type: 'string' },
                { name: 'start_time', type: 'date' },
                { name: 'end_time', type: 'date' },
                { name: 'comment', type: 'string' },
                { name: 'time_minutes', type: 'int' },
                { name: 'time_hours', type: 'float' },
                { name: 'approved', type: 'string' },
            ],
            url: "{{ route('hr.timeinput.data') }}"
        };

        var dataAdapter = new $.jqx.dataAdapter(source);

        $("#timeInputGrid").jqxGrid({
            width: '100%',
            source: dataAdapter,
            pageable: true,
            autoheight: true,
            sortable: true,
            filterable: true,
            columnsresize: true,
            columns: [
                { text: 'ID', datafield: 'id', width: 70 },
                { text: 'User', datafield: 'user', width: 150 },
                { text: 'Start Time', datafield: 'start_time', width: 180, cellsformat: 'yyyy-MM-dd HH:mm' },
                { text: 'End Time', datafield: 'end_time', width: 180, cellsformat: 'yyyy-MM-dd HH:mm' },
                { text: 'Comment', datafield: 'comment', width: 200 },
                { text: 'Minutes', datafield: 'time_minutes', width: 100 },
                { text: 'Hours', datafield: 'time_hours', width: 100 },
                { text: 'Approved', datafield: 'approved', width: 90 }
            ]
        });
    });
</script>
@endpush
