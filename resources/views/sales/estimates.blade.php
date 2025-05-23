@extends('layouts.app')

@section('title', 'Orca Packaging')

@push('styles')
    <link rel="stylesheet" href="{{ asset('sales/css/style.css') }}?v={{ time() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div id="soumission">
        <h1 id="titre-soumission"> Nouvelle Soumission </h1>

        @include('sales.indexParts.paramsBaseCommande')

        <div class="divVerticale" style="margin-top: 40px;">
            <button type="submit" name="soumettreSoumission" id="soumettreSoumission">Enregistrer la soumission</button>
        </div>
    </div>

    <div id="centergrid">
        <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left; width: 80%;">
            <div id="jqxgrid"></div>
        </div>

        <div class="align-content">
            <input type="button" id="gererBouton" value="Ajouter des objets">
            <input type="button" id="supprimerBouton" value="Supprimer">
            <input type="button" id="copierBouton" value="Dupliquer">
            <input type="button" id="modifierBouton" value="Modifier">
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/jqwidgets/jqx-all.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#clients').select2({
                placeholder: "Tape pour chercher...",
                allowClear: true
            });
        });
    </script>

    <script src="{{ asset('sales/js/validationInputs.js') }}"></script>
    <script src="{{ asset('sales/js/setupDate.js') }}"></script>
    <script src="{{ asset('sales/js/boutonsIndex.js') }}"></script>
    <script src="{{ asset('sales/js/gestionBouton.js') }}"></script>

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2FX5PV9DNT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){ dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-2FX5PV9DNT');
    </script>
    <script>
        $(document).ready(function () {
            const source = {
                datatype: "json",
                datafields: [
                    { name: 'ID', type: 'number' },
                    { name: 'Client', type: 'string' },
                    { name: 'Nom', type: 'string' },
                    { name: 'Prenom', type: 'string' },
                    { name: 'Email', type: 'string' },
                    { name: 'Telephone', type: 'string' },
                    { name: 'Nom_Travail', type: 'string' },
                    { name: 'Date_Livraison', type: 'date' }
                ],
                url: "{{ url('/sales/estimates/gridData') }}"
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
                    { text: 'N° Client', datafield: 'Client', width: 100, cellsalign: 'center' },
                    { text: 'Nom', datafield: 'Nom', width: 200 },
                    { text: 'Prenom', datafield: 'Prenom', width: 200 },
                    { text: 'Email', datafield: 'Email', width: 200 },
                    { text: 'Telephone', datafield: 'Telephone', width: 150 },
                    { text: 'Nom Travail', datafield: 'Nom_Travail'},
                    { text: 'Date de Livraison', datafield: 'Date_Livraison', width: 150, cellsalign: 'center', cellsformat: 'yyyy-MM-dd', columntype: 'datetimeinput', filtertype: 'date' }
                ]
            });
        });
    </script>
@endpush
