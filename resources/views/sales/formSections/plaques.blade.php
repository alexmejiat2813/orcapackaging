<h1>Plaques</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="prixPlaquesFournisseur">Prix des plaques fournisseurs ($CAD)</label>
        <input name="prixPlaquesFournisseur" type="number" id="prixPlaquesFournisseur" value="{{ old('prixPlaquesFournisseur') }}" required>
        @error('prixPlaquesFournisseur') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixVenteClientPlaque">Prix de vente au client ($CAD)</label>
        <input name="prixVenteClientPlaque" type="number" id="prixVenteClientPlaque" value="{{ old('prixVenteClientPlaque') }}" required>
        @error('prixVenteClientPlaque') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>
