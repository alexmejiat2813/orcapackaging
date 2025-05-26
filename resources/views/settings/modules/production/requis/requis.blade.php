@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Configuration des Requis</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('settings') }}">Settings</a></li>
                <li class="breadcrumb-item active">Requis</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs" id="requisTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-requis" type="button" role="tab">Requis</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-status" type="button" role="tab">États de production</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-condition" type="button" role="tab">Conditions</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-estimation" type="button" role="tab">Estimations</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-planification" type="button" role="tab">Planification</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dependence" type="button" role="tab">Dépendances</button>
        </li>
    </ul>




    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="tab-requis" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Liste des Requis</h5>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#requisInfoModal">
                    ℹ️ Info
                </button>
            </div>
            <div id="gridRequis"></div>
        </div>
        <div class="tab-pane fade" id="tab-status" role="tabpanel">
            <div id="gridStatus"></div>
        </div>
        <div class="tab-pane fade" id="tab-condition" role="tabpanel">
            <div id="gridCondition"></div>
        </div>
        <div class="tab-pane fade" id="tab-estimation" role="tabpanel">
            <div id="gridEstimation"></div>
        </div>
        <div class="tab-pane fade" id="tab-planification" role="tabpanel">
            <div id="gridPlanification"></div>
        </div>
        <div class="tab-pane fade" id="tab-dependence" role="tabpanel">
            <div id="gridDependence"></div>
        </div>
    </div>


    <div class="modal fade" id="requisInfoModal" tabindex="-1" aria-labelledby="requisInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="requisInfoModalLabel">À propos des Requis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p>
            La table <strong>Requis</strong> représente la liste des <strong>conditions préalables</strong> ou exigences nécessaires avant qu’une opération de production puisse être lancée.
            Elle joue un rôle central dans le contrôle de la chaîne de fabrication, assurant que chaque étape respecte les critères établis.
        </p>
        <p>
            Chaque enregistrement dans cette table décrit un élément à valider — par exemple : disponibilité du matériel, conformité de l’encre, présence de la plaque d’impression, etc.
        </p>
        <ul>
            <li><strong>Code</strong> : identifiant unique du requis.</li>
            <li><strong>Description</strong> : nom ou nature du requis.</li>
            <li><strong>Département</strong> : service responsable de la validation.</li>
            <li><strong>Suivi</strong> : indique si le requis doit être activement contrôlé.</li>
            <li><strong>Actif</strong> : statut du requis dans le système (actif ou désactivé).</li>
            <li><strong>Planification</strong> : indique s’il entre dans la planification de production.</li>
        </ul>
        <p>
            Les <strong>Requis</strong> peuvent aussi être liés à d'autres paramètres comme les statuts de production, les estimations, les conditions spéciales ou les dépendances avec d'autres opérations.
        </p>
        <p class="mb-0">
            En résumé, cette table permet d'assurer une <strong>production structurée, contrôlée et conforme</strong> aux standards de qualité de l’entreprise.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="/assets/jqwidgets/jqxcore.js"></script>
<script src="/assets/jqwidgets/jqxtabs.js"></script>
<script src="/assets/jqwidgets/jqxbuttons.js"></script>
<script src="/assets/jqwidgets/jqxscrollbar.js"></script>
<script src="/assets/jqwidgets/jqxmenu.js"></script>
<script src="/assets/jqwidgets/jqxgrid.js"></script>
<script src="/assets/jqwidgets/jqxgrid.sort.js"></script>
<script src="/assets/jqwidgets/jqxgrid.pager.js"></script>
<script src="/assets/jqwidgets/jqxgrid.filter.js"></script>
<script src="/assets/jqwidgets/jqxgrid.edit.js"></script>
<script src="/assets/jqwidgets/jqxgrid.selection.js"></script>
<script src="/assets/jqwidgets/jqxdata.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
    
        // Adapter de departamentos (como countriesAdapter)
        let departmentAdapter = new $.jqx.dataAdapter({
            datatype: 'json',
            datafields: [
                { name: 'Department_ID', type: 'int' },
                { name: 'Department_Description', type: 'string' }
            ],
            url: '{{ url("settings/modules/general/department") }}'
            
        }, {async: false, // ⚠️ importante para asegurar datos antes del grid
            autoBind: true});

        // Fuente de datos del grid (como gridAdapter)
        let sourceRequis = {
            datatype: 'json',
            datafields: [
                { name: 'Requis_Id', type: 'int' },
                { name: 'Requis_Code', type: 'string' },
                { name: 'Requis_Description', type: 'string' },
                { name: 'Requis_Department_Id', type: 'int' },
                { name: 'Requis_Actif', type: 'string' },
                { name: 'requis_isplanification', type: 'int' },
                { name: 'Requis_isFollow', type: 'string' },

                // como en el ejemplo: datafield + values: {source, value, name}
                {
                    name: 'Department_Description',
                    value: 'Requis_Department_Id',
                    values: {
                        source: departmentAdapter.records,
                        value: 'Department_ID',
                        name: 'Department_Description'
                    }
                },

            ],
            id: 'Requis_Id',
            url: '{{ url("settings/modules/production/requis/requis/data") }}'
        };

        let requisAdapter = new $.jqx.dataAdapter(sourceRequis);

        // jqxGrid
        $('#gridRequis').jqxGrid({
            width: '100%', autoheight: true, pageable: true, sortable: true, filterable: true, editable: true,
            selectionmode: 'singlecell',
            source: requisAdapter,
            columns: [
                { text: 'Code', datafield: 'Requis_Code', width: '10%', align: 'center', cellsalign: 'center' },
                { text: 'Description', datafield: 'Requis_Description', width: '30%', align: 'center', cellsalign: 'center' },
                {
                    text: 'Département',
                    datafield: 'Requis_Department_Id',     // clave guardada
                    displayfield: 'Department_Description',       // texto visible
                    columntype: 'dropdownlist',
                    width: '45%', align: 'center', cellsalign: 'center', 
                    createeditor: function (row, value, editor) {
                        editor.jqxDropDownList({
                            source: departmentAdapter,
                            displayMember: 'Department_Description',
                            valueMember: 'Department_ID'
                        });
                    }
                },                                
                { text: 'Planifié', datafield: 'requis_isplanification', columntype: 'checkbox', width: '5%', align: 'center', cellsalign: 'center' },
                { text: 'Suivi', datafield: 'Requis_isFollow', columntype: 'checkbox', width: '5%', align: 'center', cellsalign: 'center' },
                { text: 'Actif', datafield: 'Requis_Actif', columntype: 'checkbox', width: '5%', align: 'center', cellsalign: 'center' }
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
            url: '/settings/modules/production/requis/requis-productions-status'
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
            url: '/settings/modules/production/requis/requis-condition'
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
            url: '/settings/modules/production/requis/requis-estimation'
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
            url: '/settings/modules/production/requis/requis-planified-from'
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
            url: '/settings/modules/production/requis/requis-productions-status-complete'
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
