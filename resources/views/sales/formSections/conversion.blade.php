<h1>Conversion</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="quiVaProduireConversion">Qui va produire ?</label>
        <select name="quiVaProduireConversion" id="quiVaProduireConversion" required>
            <option value="orca" {{ old('quiVaProduireConversion') == 'orca' ? 'selected' : '' }}>Orca</option>
            <option value="client" {{ old('quiVaProduireConversion') == 'client' ? 'selected' : '' }}>Client</option>
            <option value="sous-traitance" {{ old('quiVaProduireConversion') == 'sous-traitance' ? 'selected' : '' }}>Sous-Traitance</option>
        </select>
        @error('quiVaProduireConversion') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="sacsParHeure">Sacs par heure</label>
        <input name="sacsParHeure" type="number" id="sacsParHeure" value="{{ old('sacsParHeure') }}" required>
        @error('sacsParHeure') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="salaireConversion">Salaire ($CAD)</label>
        <input name="salaireConversion" type="number" id="salaireConversion" value="{{ old('salaireConversion') }}" required>
        @error('salaireConversion') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<h2>Temps de production</h2>

<div class="parametres">

    <div class="divParametres">
        <label for="dureeTotaleConversion">Durée totale de conversion (en minutes)</label>
        <input name="dureeTotaleConversion" type="number" id="dureeTotaleConversion" value="{{ old('dureeTotaleConversion') }}" required>
        @error('dureeTotaleConversion') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="dureeMontageConversion">Montage (en minutes)</label>
        <input name="dureeMontageConversion" type="number" id="dureeMontageConversion" value="{{ old('dureeMontageConversion') }}" required>
        @error('dureeMontageConversion') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="dureeMenageConversion">Ménage (en minutes)</label>
        <input name="dureeMenageConversion" type="number" id="dureeMenageConversion" value="{{ old('dureeMenageConversion') }}" required>
        @error('dureeMenageConversion') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="tempsTotalConversion">Temps total (en minutes)</label>
        <input name="tempsTotalConversion" type="number" id="tempsTotalConversion" value="{{ old('tempsTotalConversion') }}" required>
        @error('tempsTotalConversion') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">
    <div class="divParametres">
        <label for="niveauDifficulte"> Niveau de difficulte </label>
        <select name="niveauDifficulte" id="niveauDifficulte" required>
            <option value="1">1 - Tres simple</option>
            <option value="2">2 - Simple</option>
            <option value="3">3 - Moyen</option>
            <option value="4">4 - Complexe</option>
            <option value="5">5 - Tres complexe</option>
        </select>
    </div>
    <div class="divParametres">
        <label for="typeScellage"> Type de scellage </label>
        <select name="typeScellage" id="typeScellage" required>
            <option value="cote">de cote</option>
            <option value="fond">de fond</option>
        </select>
    </div>
    <div class="divParametres">
        <label for="nbTrousAeration"> Nombre de trous d'aeration </label>
        <input name="nbTrousAeration" type="number" id="nbTrousAeration" required>
    </div>
    <div class="divParametres">
        <label for="diametreTrous"> Diametre des trous </label>
        <select name="diametreTrous" id="diametreTrous" required>
            <option value="na">Ne s'applique pas</option>
            <option value="1/8">1/8"</option>
            <option value="1/4">1/4"</option>
            <option value="3/8">3/8"</option>
            <option value="1/2">1/2"</option>
        </select>
    </div>
    <div class="divParametres">
        <label for="positionTrous"> Position des trous </label>
        <input name="positionTrous" type="text" id="positionTrous" required>
    </div>
</div>