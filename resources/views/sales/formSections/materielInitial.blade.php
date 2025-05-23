<h1>Matériel Initial</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="quiVaFournirMateriel">Qui va fournir ?</label>
        <select name="quiVaFournirMateriel" id="quiVaFournirMateriel" required>
            <option value="orca" {{ old('quiVaFournirMateriel') == 'orca' ? 'selected' : '' }}>Orca</option>
            <option value="client" {{ old('quiVaFournirMateriel') == 'client' ? 'selected' : '' }}>Client</option>
            <option value="sous-traitance" {{ old('quiVaFournirMateriel') == 'sous-traitance' ? 'selected' : '' }}>Sous-Traitance</option>
        </select>
        @error('quiVaFournirMateriel') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="typePellicule">Type de pellicule</label>
        <select name="typePellicule" id="typePellicule" required>
            <option value="LDPE" {{ old('typePellicule') == 'LDPE' ? 'selected' : '' }}>Low-Density Polyethylene (LDPE)</option>
            <option value="HDPE" {{ old('typePellicule') == 'HDPE' ? 'selected' : '' }}>High-Density Polyethylene (HDPE)</option>
            <option value="PP" {{ old('typePellicule') == 'PP' ? 'selected' : '' }}>Polypropylene (PP)</option>
        </select>
        @error('typePellicule') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="couleurPellicule">Couleur de la pellicule</label>
        <select name="couleurPellicule" id="couleurPellicule" required>
            <option value="claire" {{ old('couleurPellicule') == 'claire' ? 'selected' : '' }}>Claire</option>
            <option value="noir" {{ old('couleurPellicule') == 'noir' ? 'selected' : '' }}>Noir</option>
            <option value="blanc" {{ old('couleurPellicule') == 'blanc' ? 'selected' : '' }}>Blanche</option>
        </select>
        @error('couleurPellicule') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="typeMateriauInitial">Type du matériau initial</label>
        <select name="typeMateriauInitial" id="typeMateriauInitial" required>
            <option value="standard" {{ old('typeMateriauInitial') == 'standard' ? 'selected' : '' }}>Standard</option>
            <option value="heavy-duty" {{ old('typeMateriauInitial') == 'heavy-duty' ? 'selected' : '' }}>Heavy Duty</option>
            <option value="COEX" {{ old('typeMateriauInitial') == 'COEX' ? 'selected' : '' }}>COEX</option>
        </select>
        @error('typeMateriauInitial') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="formatMateriauInitial">Format du matériau initial</label>
        <select name="formatMateriauInitial" id="formatMateriauInitial" required>
            <option value="sheeting" {{ old('formatMateriauInitial') == 'sheeting' ? 'selected' : '' }}>Sheeting</option>
            <option value="u-film" {{ old('formatMateriauInitial') == 'u-film' ? 'selected' : '' }}>U-Film</option>
            <option value="j-film" {{ old('formatMateriauInitial') == 'j-film' ? 'selected' : '' }}>J-film</option>
        </select>
        @error('formatMateriauInitial') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="divVerticale">
    <div class="parametres">

        <div class="divParametres">
            <label for="web">Web</label>
            <input name="web" type="number" id="web" value="{{ old('web') }}" required>
            @error('web') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="epaisseur">Épaisseur</label>
            <input name="epaisseur" type="number" id="epaisseur" value="{{ old('epaisseur') }}" required>
            @error('epaisseur') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="coutParLivre">Coût par livre</label>
            <input name="coutParLivre" type="number" id="coutParLivre" value="{{ old('coutParLivre') }}" required>
            @error('coutParLivre') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="lbParMil">Lb/Mil</label>
            <input name="lbParMil" type="number" id="lbParMil" value="{{ old('lbParMil') }}" required>
            @error('lbParMil') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="totalPieds">Total de pieds</label>
            <input name="totalPieds" type="number" id="totalPieds" value="{{ old('totalPieds') }}" required>
            @error('totalPieds') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="totalLivres">Total de livres</label>
            <input name="totalLivres" type="number" id="totalLivres" value="{{ old('totalLivres') }}" required>
            @error('totalLivres') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

    </div>
</div>

<div class="divVerticale">
    <h2>Quantité totale finale</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="totalPiedsPlusTolerance">Total de Pieds + Tolérance</label>
            <input name="totalPiedsPlusTolerance" type="number" id="totalPiedsPlusTolerance" value="{{ old('totalPiedsPlusTolerance') }}" required>
        </div>
        <div class="divParametres">
            <label for="totalLivresPlusTolerance">Total de Livres + Tolérance</label>
            <input name="totalLivresPlusTolerance" type="number" id="totalLivresPlusTolerance" value="{{ old('totalLivresPlusTolerance') }}" required>
        </div>
        <div class="divParametres">
            <label for="coutTotal">Coût Total (en $CAD)</label>
            <input name="coutTotal" type="number" id="coutTotal" value="{{ old('coutTotal') }}" required>
        </div>
    </div>
</div>
