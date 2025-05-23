<h1>Encre et solvant</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="quiVaFournirEncre">Qui va fournir ?</label>
        <select name="quiVaFournirEncre" id="quiVaFournirEncre" required>
            <option value="orca" {{ old('quiVaFournirEncre') == 'orca' ? 'selected' : '' }}>Orca</option>
            <option value="client" {{ old('quiVaFournirEncre') == 'client' ? 'selected' : '' }}>Client</option>
            <option value="sous-traitance" {{ old('quiVaFournirEncre') == 'sous-traitance' ? 'selected' : '' }}>Sous-Traitance</option>
        </select>
        @error('quiVaFournirEncre') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="typeEncre">Type d'encre</label>
        <select name="typeEncre" id="typeEncre" required>
            @foreach(['exterieur', 'surface', 'hercubone', 'thermoplast', 'process', 'xks'] as $type)
                <option value="{{ $type }}" {{ old('typeEncre') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        @error('typeEncre') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="nbEncres">Nombre d'encres</label>
        <select name="nbEncres" id="nbEncres" class="nbEncres" required>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('nbEncres') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        @error('nbEncres') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="poucesCarresParSac">Pouces carrés par sac</label>
        <input name="poucesCarresParSac" type="number" id="poucesCarresParSac" value="{{ old('poucesCarresParSac') }}">
        @error('poucesCarresParSac') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="poucesCarresParQuantiteAProduire">Pouces carrés par quantité à produire</label>
        <input name="poucesCarresParQuantiteAProduire" type="number" id="poucesCarresParQuantiteAProduire" value="{{ old('poucesCarresParQuantiteAProduire') }}">
        @error('poucesCarresParQuantiteAProduire') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="constante">Constante</label>
        <input name="constante" type="number" id="constante" step="any" value="{{ old('constante', 0.00000331767) }}">
        @error('constante') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>

<h1>Couleurs de base</h1>
<div class="encresContainer">
    {{-- Contenu dynamique JS ici --}}
</div>

<div class="parametres">
    <div class="divParametres">
        <label for="totalKilosEncre">Total de kilos d'encre nécessaire</label>
        <input name="totalKilosEncre" type="number" id="totalKilosEncre" value="{{ old('totalKilosEncre') }}">
        @error('totalKilosEncre') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutTotalEncre">Coût total de l'encre ($CAD)</label>
        <input name="coutTotalEncre" type="number" id="coutTotalEncre" value="{{ old('coutTotalEncre') }}">
        @error('coutTotalEncre') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>

<h2>Solvant</h2>
<div class="divVerticale">
    <div class="parametres">

        <div class="divParametres">
            <label for="quantiteKGSolvant">Quantité KG</label>
            <input name="quantiteKGSolvant" type="number" id="quantiteKGSolvant" value="{{ old('quantiteKGSolvant') }}">
            @error('quantiteKGSolvant') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="coutKGSolvant">Coût par KG ($CAD)</label>
            <input name="coutKGSolvant" type="number" id="coutKGSolvant" value="{{ old('coutKGSolvant') }}">
            @error('coutKGSolvant') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="divParametres">
            <label for="coutTotalSolvant">Coût total du solvant ($CAD)</label>
            <input name="coutTotalSolvant" type="number" id="coutTotalSolvant" value="{{ old('coutTotalSolvant') }}">
            @error('coutTotalSolvant') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

    </div>
</div>
