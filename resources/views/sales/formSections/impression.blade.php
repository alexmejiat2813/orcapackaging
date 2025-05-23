<h1>Impression</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="quiVaImprimer">Qui va imprimer ?</label>
        <input name="quiVaImprimer" type="text" id="quiVaImprimer" value="{{ old('quiVaImprimer') }}" required>
        @error('quiVaImprimer') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="piedsParHeure">Pieds par heure</label>
        <input name="piedsParHeure" type="number" id="piedsParHeure" value="{{ old('piedsParHeure') }}" required>
        @error('piedsParHeure') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="salaireImpression">Salaire ($CAD)</label>
        <input name="salaireImpression" type="number" id="salaireImpression" value="{{ old('salaireImpression') }}" required>
        @error('salaireImpression') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<h2>Temps de production</h2>

<div class="parametres">

    <div class="divParametres">
        <label for="dureeTotaleImpression">Durée totale d'impression (en minutes)</label>
        <input name="dureeTotaleImpression" type="number" id="dureeTotaleImpression" value="{{ old('dureeTotaleImpression') }}" required>
        @error('dureeTotaleImpression') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="dureeMontagePlaques">Montage des plaques (en minutes)</label>
        <input name="dureeMontagePlaques" type="number" id="dureeMontagePlaques" value="{{ old('dureeMontagePlaques') }}" required>
        @error('dureeMontagePlaques') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="dureeMiseEnTrain">Mise en train (en minutes)</label>
        <input name="dureeMiseEnTrain" type="number" id="dureeMiseEnTrain" value="{{ old('dureeMiseEnTrain') }}" required>
        @error('dureeMiseEnTrain') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="dureeLavage">Lavage (en minutes)</label>
        <input name="dureeLavage" type="number" id="dureeLavage" value="{{ old('dureeLavage') }}" required>
        @error('dureeLavage') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="tempsTotalProduction">Temps total d'impression (en minutes)</label>
        <input name="tempsTotalProduction" type="number" id="tempsTotalProduction" value="{{ old('tempsTotalProduction') }}" required>
        @error('tempsTotalProduction') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">
    <div class="divParametres">
        <label for="prixStickyBagPoucesCarres"> Prix Sticky Bag ($ / Pouces Carres) </label>
        <input name="prixStickyBagPoucesCarres" type="number" id="prixStickyBagPoucesCarres">
    </div>
    <div class="divParametres">
        <label for="prixStickyBagTotal"> Prix Sticky Bag Cout Total ($CAD) </label>
        <input name="prixStickyBagTotal" type="number" id="prixStickyBagTotal">
    </div>
    <div class="divParametres">
        <label for="upc"> UPC </label>
        <input name="upc" type="number" id="upc">
    </div>
    <div class="divParametres">
        <label for="imprimeEyeMark"> Imprime de Eye Mark </label>
        <select name="imprimeEyeMark" id="imprimeEyeMark">
            <option value="">--- Selectionner ---</option>
            <option value="plain">Plain</option>
            <option value="imprime-orca">Imprime Orca</option>
        </select>
    </div>
    <div class="divParametres">
        <label for="typeEyeMark"> Type de Eye Mark </label>
        <select name="quiVaFournirEncre" id="quiVaFournirEncre">
            <option value="">--- Selectionner ---</option>
            <option value="standard">Standard</option>
            <option value="lecteur-optique">Speciale : Lecteur Optique</option>
        </select>
    </div>
</div>
