@extends('layouts.app')

@section('title', 'Orca Packaging')

@push('styles')
    <link rel="stylesheet" href="{{ asset('sales/css/style.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="page-estimate-create container-fluid py-4">
        <div class="section-top divVerticale">
            <div id="jqxWidget">
                <div id="jqxgrid"></div>
            </div>
        
            <div class="action-buttons">
                <input type="button" id="modifierItemBouton" value="Modifier l'objet" class="btn btn-outline-primary">
                <input type="button" id="supprimerItemBouton" value="Supprimer" class="btn btn-outline-danger">
                <input type="button" id="copierItemBouton" value="Dupliquer" class="btn btn-outline-secondary">
            </div>
        </div>
        
        <div id="principale" class="section-main">
            <h1 class="h1-main" id="titre-item">Nouvel Item</h1>
        
            @include('sales.indexParts.paramsBaseItem')

            <div id="formulaireModificationSection" class="mt-4 d-none">
                <h1> Commentaire de modification</h1>

                <div id="modificationGrid" style="height: 300px;"></div>

                <div class="mt-3">
                    <label for="modificationCommentaire" class="form-label">Commentaire</label>
                    <textarea id="modificationCommentaire" name="modificationCommentaire" rows="4" class="form-control" placeholder="Ajoutez un commentaire sur cette modification..."></textarea>
                </div>
            </div>
        
            <div class="divVerticale mt-4">
                <button type="submit" name="soumettreItem" id="soumettreItem" class="btn btn-primary bouton-formulaire">
                    <div class="divVerticale align-items-center">
                        <p class="btn-text mb-1">Soumettre l'objet</p>
                        <svg class="spinner d-none" width="20" height="20" viewBox="0 0 50 50">
                            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('sales/js/gestionBouton.js') }}"></script>
    <script src="{{ asset('sales/js/item.js') }}"></script>
    <script>
        $(document).ready(function () {
            const source = {
                datatype: "json",
                datafields: [
                    { name: 'ID', type: 'number' },
                    { name: 'isReady', type: 'bool' },
                    { name: 'descriptionProduit', type: 'string' },
                    { name: 'quantite', type: 'number' },
                    { name: 'commande', type: 'string' },

                ],
                url: "{{ url('/sales/estimates_item/gridData') }}"
            };

            const dataAdapter = new $.jqx.dataAdapter(source);

            $("#jqxgrid").jqxGrid({
            width: '100%',
            source: dataAdapter,
            pageable: true,
            autoheight: true,
            sortable: true,
            filterable: true,
            showfilterrow: true,
            columnsresize: true,
            editable : true,
            editmode: 'click',
            selectionmode: 'singlerow',
            columns: [
                { text: 'ID', datafield: 'ID', width: 60, editable : false, cellsalign: 'center' },
                { text: 'Commande acceptee ?', datafield: 'isReady', columntype: 'checkbox', editable : true, width: 100, cellsalign: 'center'},
                { text: 'Description Item', datafield: 'descriptionProduit', editable : false },
                { text: 'Quantite', datafield: 'quantite', width: 100, cellsalign: 'center', editable : false },
                { text: 'Type Commande', datafield: 'commande', width: 150, cellsalign: 'center', editable : false }
            ]});

            $('#jqxgrid').on('cellendedit', function (event) {
                const args = event.args;
                const rowindex = args.rowindex;
                const datafield = args.datafield;
                const oldvalue = args.oldvalue;
                const newvalue = args.newvalue;

                if (datafield === "isReady") {
                    const rowData = $('#jqxgrid').jqxGrid('getrowdata', rowindex);
                
                    // Si déjà TRUE, on empêche toute modification
                    if (oldvalue === true) {
                        setTimeout(() => {
                            $('#jqxgrid').jqxGrid('setcellvalue', rowindex, 'isReady', true);
                        }, 100); 
                        return;
                    }
                
                    // Confirmation utilisateur
                    const confirmAction = confirm("Confirmez-vous que cet item est prêt ? Cette action est irréversible.");
                    if (!confirmAction) {
                        setTimeout(() => {
                            $('#jqxgrid').jqxGrid('setcellvalue', rowindex, 'isReady', false);
                        }, 50);
                        return;
                    } 

                    fetch('/sales/estimates_item/itemReady', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: rowData.ID })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la requête');
                        }
                        return response.json();
                    })
                    .catch(error => {
                        alert("❌ Erreur lors de la mise à jour.");
                        $('#jqxgrid').jqxGrid('setcellvalue', rowindex, 'isReady', false); // rollback
                    });
                }
            });
        });
    </script>
    <script src="{{ asset('sales/js/boutonsItem.js') }}"></script>
@endpush