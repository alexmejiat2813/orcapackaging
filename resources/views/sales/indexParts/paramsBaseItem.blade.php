<form id="form-inputsCommunItem" class="form-to-validate" method="POST">
    @csrf

    <div class="parametres">
        <div class="divParametres">
            <label for="profit">Profit</label>
            <div class="divHorizontale">
                <input name="profit" type="number" id="profit" min="0" max="100" step="0.1" value="{{ old('profit', 0) }}">
                <span>%</span>
            </div>
            @error('profit') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="commission">Commission</label>
            <div class="divHorizontale">
                <input name="commission" type="number" id="commission" min="0" max="100" step="0.1" value="{{ old('commission', 0) }}">
                <span>%</span>
            </div>
            @error('commission') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="tolerance">Tolérance</label>
            <div class="divHorizontale">
                <input name="tolerance" type="number" id="tolerance" min="0" max="100" step="0.1" value="{{ old('tolerance', 0) }}">
                <span>%</span>
            </div>
            @error('tolerance') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="frais_admin">Frais Admin</label>
            <div class="divHorizontale">
                <input name="frais_admin" type="number" id="frais_admin" min="0" max="100" step="0.1" value="{{ old('frais_admin', 0) }}">
                <span>%</span>
            </div>
            @error('frais_admin') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="descriptionProduit">Description du produit</label>
            <input name="descriptionProduit" type="text" id="descriptionProduit" value="{{ old('descriptionProduit') }}" required>
            @error('descriptionProduit') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="quantite">Quantité</label>
            <input name="quantite" type="number" id="quantite" value="{{ old('quantite') }}" required>
            @error('quantite') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="prix">Prix (en $CAD)</label>
            <input name="prix" type="number" id="prix" value="{{ old('prix') }}" required>
            @error('prix') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="typeDePrix">Type de prix</label>
            <select name="typeDePrix" id="typeDePrix" required>
                <option value="unite" {{ old('typeDePrix') == 'unite' ? 'selected' : '' }}>Unité</option>
                <option value="mille" {{ old('typeDePrix') == 'mille' ? 'selected' : '' }}>Par mille</option>
            </select>
            @error('typeDePrix') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>

    <div id="container-commande">
        <select name="commande" id="commande" onchange="afficherTexte()">
            <option value="">-- Sélectionne une option --</option>
            @foreach($options as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
</form>

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