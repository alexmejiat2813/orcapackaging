<h1>Emballage</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="boitesParPalettes">Boîtes par palette</label>
        <input name="boitesParPalettes" type="number" id="boitesParPalettes" value="{{ old('boitesParPalettes') }}" required>
        @error('boitesParPalettes') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="totalSacsParPalette">Total de sacs par palette</label>
        <input name="totalSacsParPalette" type="number" id="totalSacsParPalette" value="{{ old('totalSacsParPalette') }}" required>
        @error('totalSacsParPalette') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">

    <div class="divParametres">
        <label for="sacsParBoite">Sacs par boîte</label>
        <input name="sacsParBoite" type="number" id="sacsParBoite" value="{{ old('sacsParBoite') }}" required>
        @error('sacsParBoite') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="nbBoites">Nombre de boîtes</label>
        <input name="nbBoites" type="number" id="nbBoites" value="{{ old('nbBoites') }}" required>
        @error('nbBoites') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutBoite">Coût de la boîte ($CAD)</label>
        <input name="coutBoite" type="number" id="coutBoite" value="{{ old('coutBoite', 0.65) }}" required>
        @error('coutBoite') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="totalPalettes">Total de palettes</label>
        <input name="totalPalettes" type="number" id="totalPalettes" value="{{ old('totalPalettes') }}" required>
        @error('totalPalettes') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutPalette">Coût de la palette ($CAD)</label>
        <input name="coutPalette" type="number" id="coutPalette" value="{{ old('coutPalette') }}" required>
        @error('coutPalette') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>
