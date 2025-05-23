<div id="container-commande">
    <select id="commande" onchange="afficherTexte()">
        <option value="">-- Sélectionne une option --</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>

{{-- Zones dynamiques à afficher --}}
<div id="zoneSacsImpr" style="display: none;">
    <h1 id="texteSacsImpr" class="texte">Sacs imprimés</h1>
    @include('sales.indexParts.sacsImprimes')
</div>

<div id="zoneSacsNonImpr" style="display: none;">
    <h1 id="texteSacsNonImpr" class="texte">Sacs non imprimés</h1>
    @include('sales.indexParts.sacsNonImprimes')
</div>

<div id="zoneRouleaux" style="display: none;">
    <h1 id="texteRouleaux" class="texte">Rouleaux imprimés</h1>
    @include('sales.indexParts.rouleaux')
</div>

<div id="zoneSacsPapier" style="display: none;">
    <h1 id="texteSacsPapier" class="texte">Sacs en papier</h1>
    @include('sales.indexParts.sacsPapier')
</div>

<div id="zoneTape" style="display: none;">
    <h1 id="texteTape" class="texte">Rubans adhésifs</h1>
    @include('sales.indexParts.tape')
</div>
