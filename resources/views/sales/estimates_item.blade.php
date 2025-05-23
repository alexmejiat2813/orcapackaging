@extends('layouts.app')

@section('title', 'Orca Packaging')

@push('styles')
    <link rel="stylesheet" href="{{ asset('sales/css/style.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div id="centergrid">
        <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left; width: 80%;">
                <div id="jqxgrid"></div>
        </div>

        <div class="align-content">
            <input type="button" id="modifierItemBouton" value="Modifier l'objet">
            <input type="button" id="supprimerItemBouton" value="Supprimer">
            <input type="button" id="copierItemBouton" value="Dupliquer">
        </div>
    </div>
    <div id="principale">
        <h1 id="titre-item">Nouvel Item</h1>

        @include('sales.indexParts.paramsBaseItem')

        <div class="divVerticale" style="margin-top: 40px;">
            <button type="submit" name="soumettreItem" id="soumettreItem" style="display: flex;flex-direction: column;">
                Soumettre l'objet
            </button>
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
            selectionmode: 'singlerow',
            columns: [
                { text: 'ID', datafield: 'ID', width: 60, cellsalign: 'center' },
                { text: 'Description Item', datafield: 'descriptionProduit' },
                { text: 'Quantite', datafield: 'quantite', width: 100, cellsalign: 'center' },
                { text: 'Type Commande', datafield: 'commande', width: 150, cellsalign: 'center' }
            ]});
        });
    </script>
    <script src="{{ asset('sales/js/boutonsItem.js') }}"></script>
@endpush