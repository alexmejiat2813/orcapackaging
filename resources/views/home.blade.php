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

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('hr.clock.process') }}">
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
    $(document).ready(function () {
        // Data source for the grid
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
                { name: 'weekly_hours', type: 'string' },
            ],
            url: "{{ route('hr.timeinput.data') }}"
        };

        // Data adapter for jqxGrid
        var dataAdapter = new $.jqx.dataAdapter(source);

        // Initialize the grid
        $("#timeInputGrid").jqxGrid({
            width: '100%',
            source: dataAdapter,
            pageable: true,
            autoheight: true,
            sortable: true,
            filterable: true,
            columnsresize: true,
            columns: [
                //{ text: 'ID', datafield: 'id', width: 70 },
                { text: 'User', datafield: 'user', width: '33.3%', align: 'center', cellsalign: 'center' },
                { text: 'Start Time', datafield: 'start_time', width: '33.3%', cellsformat: 'yyyy-MM-dd HH:mm', align: 'center', cellsalign: 'center' },
                { text: 'Weekly Hours', datafield: 'weekly_hours', width: '33.3%', align: 'center', cellsalign: 'center' },
                //{ text: 'End Time', datafield: 'end_time', width: 180, cellsformat: 'yyyy-MM-dd HH:mm' },
                //{ text: 'Comment', datafield: 'comment', width: 200 },
                //{ text: 'Minutes', datafield: 'time_minutes', width: 100 },
                //{ text: 'Hours', datafield: 'time_hours', width: 100 },
                //{ text: 'Approved', datafield: 'approved', width: 90 }
            ]
        });
    });
</script>
@endpush
