<div class="divVerticale">
    <h2>Mesures du produit final</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="largeur">Largeur</label>
            <input name="largeur" type="number" id="largeur" value="{{ old('largeur') }}" required>
            @error('largeur') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="hauteur">Hauteur</label>
            <input name="hauteur" type="number" id="hauteur" value="{{ old('hauteur') }}" required>
            @error('hauteur') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="poignee">Poignée ou Lip</label>
            <input name="poignee" type="number" id="poignee" value="{{ old('poignee') }}">
            @error('poignee') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="gousset">Gousset</label>
            <input name="gousset" type="number" id="gousset" value="{{ old('gousset') }}">
            @error('gousset') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>