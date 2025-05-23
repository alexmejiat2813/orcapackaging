<h1>Variation du prix selon la quantité</h1>

<div class="parametres">
    <div class="divParametres">
        <label for="quantiteInformative1">Quantité</label>
        <input name="quantiteInformative1" type="number" id="quantiteInformative1" value="{{ old('quantiteInformative1') }}" readonly>
        @error('quantiteInformative1') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixInformatif1">$CAD</label>
        <input name="prixInformatif1" type="number" id="prixInformatif1" value="{{ old('prixInformatif1') }}" readonly>
        @error('prixInformatif1') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>

<div class="parametres">
    <div class="divParametres">
        <label for="quantiteInformative2">Quantité</label>
        <input name="quantiteInformative2" type="number" id="quantiteInformative2" value="{{ old('quantiteInformative2') }}" readonly>
        @error('quantiteInformative2') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixInformatif2">$CAD</label>
        <input name="prixInformatif2" type="number" id="prixInformatif2" value="{{ old('prixInformatif2') }}" readonly>
        @error('prixInformatif2') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>

<div class="parametres">
    <div class="divParametres">
        <label for="quantiteInformative3">Quantité</label>
        <input name="quantiteInformative3" type="number" id="quantiteInformative3" value="{{ old('quantiteInformative3') }}" readonly>
        @error('quantiteInformative3') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixInformatif3">$CAD</label>
        <input name="prixInformatif3" type="number" id="prixInformatif3" value="{{ old('prixInformatif3') }}" readonly>
        @error('prixInformatif3') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>
