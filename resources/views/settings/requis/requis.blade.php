@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Configuration des Requis</h3>

    <div id="requisTabs">
        <ul>
            <li>Requis</li>
            <li>États de production</li>
            <li>Conditions</li>
            <li>Estimations</li>
            <li>Planification</li>
            <li>Dépendances</li>
        </ul>

        <div id="tab-requis">
            <div id="gridRequis"></div>
        </div>
        <div id="tab-status">
            <div id="gridStatus"></div>
        </div>
        <div id="tab-condition">
            <div id="gridCondition"></div>
        </div>
        <div id="tab-estimation">
            <div id="gridEstimation"></div>
        </div>
        <div id="tab-planification">
            <div id="gridPlanification"></div>
        </div>
        <div id="tab-dependence">
            <div id="gridDependence"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#requisTabs').jqxTabs({ width: '100%', height: 600 });

        // Requis
        let sourceRequis = {
            datatype: 'json',
            datafields: [
                { name: 'Requis_Id', type: 'int' },
                { name: 'Requis_Code', type: 'string' },
                { name: 'Requis_Description', type: 'string' },
                { name: 'Requis_Description_English', type: 'string' },
                { name: 'Requis_Department_Id', type: 'int' }
            ],
            id: 'Requis_Id',
            url: '/api/requis'
        };
        let dataAdapterRequis = new $.jqx.dataAdapter(sourceRequis);
        $('#gridRequis').jqxGrid({
            width: '100%', autoheight: true, pageable: true, sortable: true, filterable: true, editable: true,
            source: dataAdapterRequis,
            columns: [
                { text: 'Code', datafield: 'Requis_Code', width: '20%' },
                { text: 'Description', datafield: 'Requis_Description', width: '40%' },
                { text: 'Description (EN)', datafield: 'Requis_Description_English', width: '30%' },
                { text: 'Département', datafield: 'Requis_Department_Id', width: '10%' }
            ]
        });

        // Production Status
        let sourceStatus = {
            datatype: 'json',
            datafields: [
                { name: 'Requis_Id', type: 'int' },
                { name: 'Production_Status_Id', type: 'int' },
                { name: 'Ordre', type: 'int' },
                { name: 'IsComplete_Follow', type: 'bool' }
            ],
            id: 'Requis_Id',
            url: '/api/requis-status'
        };
        $('#gridStatus').jqxGrid({
            width: '100%', autoheight: true, pageable: true, sortable: true, filterable: true, editable: true,
            source: new $.jqx.dataAdapter(sourceStatus),
            columns: [
                { text: 'Requis ID', datafield: 'Requis_Id', width: '20%' },
                { text: 'Statut', datafield: 'Production_Status_Id', width: '30%' },
                { text: 'Ordre', datafield: 'Ordre', width: '30%' },
                { text: 'Suivi complet', datafield: 'IsComplete_Follow', width: '20%', columntype: 'checkbox' }
            ]
        });

        // Condition
        let sourceCondition = {
            datatype: 'json',
            datafields: [
                { name: 'Requis_Id', type: 'int' },
                { name: 'Follow_Condition_Id', type: 'int' },
                { name: 'Requis_Condition_Active', type: 'bool' },
                { name: 'Production_Status_Id', type: 'int' }
            ],
            id: 'Requis_Condition_Id',
            url: '/api/requis-condition'
        };
        $('#gridCondition').jqxGrid({
            width: '100%', autoheight: true, pageable: true, sortable: true, filterable: true, editable: true,
            source: new $.jqx.dataAdapter(sourceCondition),
            columns: [
                { text: 'Requis ID', datafield: 'Requis_Id', width: '25%' },
                { text: 'Condition', datafield: 'Follow_Condition_Id', width: '25%' },
                { text: 'Actif', datafield: 'Requis_Condition_Active', width: '25%', columntype: 'checkbox' },
                { text: 'Statut', datafield: 'Production_Status_Id', width: '25%' }
            ]
        });

        // Estimation
        let sourceEstimation = {
            datatype: 'json',
            datafields: [
                { name: 'Requis_Id', type: 'int' },
                { name: 'Code_Estimation', type: 'string' }
            ],
            id: 'Requis_Estimation_Id',
            url: '/api/requis-estimation'
        };
        $('#gridEstimation').jqxGrid({
            width: '100%', autoheight: true, pageable: true, sortable: true, filterable: true, editable: true,
            source: new $.jqx.dataAdapter(sourceEstimation),
            columns: [
                { text: 'Requis ID', datafield: 'Requis_Id', width: '40%' },
                { text: 'Estimation', datafield: 'Code_Estimation', width: '60%' }
            ]
        });

        // Planification
        let sourcePlanification = {
            datatype: 'json',
            datafields: [
                { name: 'Requis_Id', type: 'int' },
                { name: 'Type_Id', type: 'int' },
                { name: 'Operation_Id', type: 'int' },
                { name: 'Equipment_Regroupment_ID', type: 'int' }
            ],
            id: 'Requis_Planified_From_ID',
            url: '/api/requis-planified'
        };
        $('#gridPlanification').jqxGrid({
            width: '100%', autoheight: true, pageable: true, sortable: true, filterable: true, editable: true,
            source: new $.jqx.dataAdapter(sourcePlanification),
            columns: [
                { text: 'Requis ID', datafield: 'Requis_Id', width: '25%' },
                { text: 'Type', datafield: 'Type_Id', width: '25%' },
                { text: 'Opération', datafield: 'Operation_Id', width: '25%' },
                { text: 'Groupe équipement', datafield: 'Equipment_Regroupment_ID', width: '25%' }
            ]
        });

        // Dépendances
        let sourceDependence = {
            datatype: 'json',
            datafields: [
                { name: 'Follow_Operation_Id', type: 'int' },
                { name: 'Closed_Operation_Id', type: 'int' },
                { name: 'Follow_Production_Status_Id', type: 'int' },
                { name: 'Follow_Type_Id', type: 'int' },
                { name: 'Closed_Type_Id', type: 'int' }
            ],
            id: 'Requis_Production_Status_Complete_Id',
            url: '/api/requis-dependence'
        };
        $('#gridDependence').jqxGrid({
            width: '100%', autoheight: true, pageable: true, sortable: true, filterable: true, editable: true,
            source: new $.jqx.dataAdapter(sourceDependence),
            columns: [
                { text: 'Suiveur', datafield: 'Follow_Operation_Id', width: '20%' },
                { text: 'Clôture', datafield: 'Closed_Operation_Id', width: '20%' },
                { text: 'Statut requis', datafield: 'Follow_Production_Status_Id', width: '20%' },
                { text: 'Type suivi', datafield: 'Follow_Type_Id', width: '20%' },
                { text: 'Type clôture', datafield: 'Closed_Type_Id', width: '20%' }
            ]
        });
    });
</script>
@endpush
