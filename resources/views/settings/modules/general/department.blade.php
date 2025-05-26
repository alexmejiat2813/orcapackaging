@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Départements</h3>

    <div id="gridDepartments"></div>
</div>
@endsection

@push('scripts')
<script src="/assets/jqwidgets/jqxcore.js"></script>
<script src="/assets/jqwidgets/jqxbuttons.js"></script>
<script src="/assets/jqwidgets/jqxscrollbar.js"></script>
<script src="/assets/jqwidgets/jqxmenu.js"></script>
<script src="/assets/jqwidgets/jqxgrid.js"></script>
<script src="/assets/jqwidgets/jqxgrid.selection.js"></script>
<script src="/assets/jqwidgets/jqxgrid.edit.js"></script>
<script src="/assets/jqwidgets/jqxgrid.sort.js"></script>
<script src="/assets/jqwidgets/jqxgrid.filter.js"></script>
<script src="/assets/jqwidgets/jqxgrid.columnsresize.js"></script>
<script src="/assets/jqwidgets/jqxgrid.pager.js"></script>
<script src="/assets/jqwidgets/jqxdata.js"></script>

<script>
    $(document).ready(function () {
        let source = {
            datatype: 'json',
            datafields: [
                { name: 'Department_ID', type: 'int' },
                { name: 'Department_Description', type: 'string' },
                { name: 'Department_DesEnglish', type: 'string' },
                { name: 'Periode_Id', type: 'int' },
                { name: 'ID_GraphICC', type: 'int' }
            ],
            id: 'Department_ID',
            url: '{{ url("settings/modules/general/department/data") }}'
        };

        let dataAdapter = new $.jqx.dataAdapter(source);

        $('#gridDepartments').jqxGrid({
            width: '100%',
            autoheight: true,
            pageable: true,
            sortable: true,
            filterable: true,
            editable: true,
            source: dataAdapter,
            columns: [
                { text: 'ID', datafield: 'Department_ID', width: '10%', editable: false },
                { text: 'Description (FR)', datafield: 'Department_Description', width: '30%' },
                { text: 'Description (EN)', datafield: 'Department_DesEnglish', width: '30%' },
                { text: 'Période ID', datafield: 'Periode_Id', width: '15%' },
                { text: 'GraphICC ID', datafield: 'ID_GraphICC', width: '15%' }
            ]
        });
    });
</script>
@endpush
