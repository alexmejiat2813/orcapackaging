<h1>Coûts totaux de production</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="coutTotauxProductionPlaques">Coût plaques ($CAD)</label>
        <input name="coutTotauxProductionPlaques" type="number" id="coutTotauxProductionPlaques" value="{{ old('coutTotauxProductionPlaques') }}" required>
        @error('coutTotauxProductionPlaques') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutTotauxProductionPellicule">Coût pellicule ($CAD)</label>
        <input name="coutTotauxProductionPellicule" type="number" id="coutTotauxProductionPellicule" value="{{ old('coutTotauxProductionPellicule') }}" required>
        @error('coutTotauxProductionPellicule') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutTotauxProductionEncre">Coût encre ($CAD)</label>
        <input name="coutTotauxProductionEncre" type="number" id="coutTotauxProductionEncre" value="{{ old('coutTotauxProductionEncre') }}" required>
        @error('coutTotauxProductionEncre') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">

    <div class="divParametres">
        <label for="coutTotauxProductionSolvant">Coût solvant ($CAD)</label>
        <input name="coutTotauxProductionSolvant" type="number" id="coutTotauxProductionSolvant" value="{{ old('coutTotauxProductionSolvant') }}" required>
        @error('coutTotauxProductionSolvant') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutTotauxProductionImpression">Coût impression ($CAD)</label>
        <input name="coutTotauxProductionImpression" type="number" id="coutTotauxProductionImpression" value="{{ old('coutTotauxProductionImpression') }}" required>
        @error('coutTotauxProductionImpression') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutTotauxProductionConversion">Coût conversion ($CAD)</label>
        <input name="coutTotauxProductionConversion" type="number" id="coutTotauxProductionConversion" value="{{ old('coutTotauxProductionConversion') }}" required>
        @error('coutTotauxProductionConversion') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">

    <div class="divParametres">
        <label for="coutTotauxProductionEmballage">Coût emballage ($CAD)</label>
        <input name="coutTotauxProductionEmballage" type="number" id="coutTotauxProductionEmballage" value="{{ old('coutTotauxProductionEmballage') }}" required>
        @error('coutTotauxProductionEmballage') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutTotauxProductionLivraison">Coût livraison ($CAD)</label>
        <input name="coutTotauxProductionLivraison" type="number" id="coutTotauxProductionLivraison" value="{{ old('coutTotauxProductionLivraison') }}" required>
        @error('coutTotauxProductionLivraison') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutTotauxProductionEntrepot">Coût entrepôt ($CAD)</label>
        <input name="coutTotauxProductionEntrepot" type="number" id="coutTotauxProductionEntrepot" value="{{ old('coutTotauxProductionEntrepot') }}" required>
        @error('coutTotauxProductionEntrepot') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>
