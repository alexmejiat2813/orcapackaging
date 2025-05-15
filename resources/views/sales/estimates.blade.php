<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Soumissions</title>

    <!-- CSS internes -->
    <link rel="stylesheet" href="{{ asset('sales/css/style.css') }}?v={{ time() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/jqwidgets/styles/jqx.base.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/jqwidgets/jqwidgets/styles/jqx.material.css') }}" type="text/css" />

    <!-- JS internes -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/jqwidgets/jqx-all.js') }}"></script>

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2FX5PV9DNT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){ dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-2FX5PV9DNT');
    </script>
</head>

<body>
    <div id="soumission">
        <h1> SOUMISSIONS </h1>

        {{-- Inclusion d’une section Blade --}}
        @include('sales.indexParts.paramsBaseCommande')

        <script>
            $(document).ready(function () {
                $('#clients').select2({
                    placeholder: "Tape pour chercher...",
                    allowClear: true
                });
            });
        </script>

        <script src="{{ asset('sales/scripts/validationInputs.js') }}"></script>
        <script src="{{ asset('sales/scripts/setupDate.js') }}"></script>

        <div class="divVerticale" style="margin-top: 40px;">
            <button type="submit" name="soumettreSoumission" id="soumettreSoumission">Enregistrer la soumission</button>
        </div>

        <script src="{{ asset('sales/scripts/gestionBouton.js') }}"></script>
    </div>

    <div id="centergrid">
        <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left; width: 80%;">
            <div id="jqxgrid"></div>
        </div>

        <div class="align-content">
            <input type="button" id="gererBouton" value="Gerer les objets">
            <input type="button" id="supprimerBouton" value="Supprimer">
            <input type="button" id="copierBouton" value="Copier">
        </div>

        <script src="{{ asset('sales/scripts/boutonsIndex.js') }}"></script>
    </div>
</body>
</html>
