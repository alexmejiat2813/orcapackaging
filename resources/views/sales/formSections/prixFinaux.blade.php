<h1>Prix finaux</h1>

<div class="parametres">

    <div class="divParametres">
        <label for="coutsFinaux">Coûts finaux</label>
        <input name="coutsFinaux" type="number" id="coutsFinaux" value="{{ old('coutsFinaux') }}" required>
        @error('coutsFinaux') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutsPlusProfit">Coûts + profit</label>
        <input name="coutsPlusProfit" type="number" id="coutsPlusProfit" value="{{ old('coutsPlusProfit') }}" required>
        @error('coutsPlusProfit') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="coutsPlusComission">Coûts + commission</label>
        <input name="coutsPlusComission" type="number" id="coutsPlusComission" value="{{ old('coutsPlusComission') }}" required>
        @error('coutsPlusComission') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">

    <div class="divParametres">
        <label for="prixFinauxSansProfit">Prix sans profit ni commission</label>
        <input name="prixFinauxSansProfit" type="number" id="prixFinauxSansProfit" value="{{ old('prixFinauxSansProfit') }}" required>
        @error('prixFinauxSansProfit') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixFinauxUniteAvecProfit">Prix par unité (avec profit + commission)</label>
        <input name="prixFinauxUniteAvecProfit" type="number" id="prixFinauxUniteAvecProfit" value="{{ old('prixFinauxUniteAvecProfit') }}" required>
        @error('prixFinauxUniteAvecProfit') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixFinauxMilleAvecProfit">Prix par 1000 unités</label>
        <input name="prixFinauxMilleAvecProfit" type="number" id="prixFinauxMilleAvecProfit" value="{{ old('prixFinauxMilleAvecProfit') }}" required>
        @error('prixFinauxMilleAvecProfit') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">

    <div class="divParametres">
        <label for="prixFinauxDixPourcent">Prix +10%</label>
        <input name="prixFinauxDixPourcent" type="number" id="prixFinauxDixPourcent" value="{{ old('prixFinauxDixPourcent') }}" required>
        @error('prixFinauxDixPourcent') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixFinauxVingtPourcent">Prix +20%</label>
        <input name="prixFinauxVingtPourcent" type="number" id="prixFinauxVingtPourcent" value="{{ old('prixFinauxVingtPourcent') }}" required>
        @error('prixFinauxVingtPourcent') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixFinauxTrentePourcent">Prix +30%</label>
        <input name="prixFinauxTrentePourcent" type="number" id="prixFinauxTrentePourcent" value="{{ old('prixFinauxTrentePourcent') }}" required>
        @error('prixFinauxTrentePourcent') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>

<div class="parametres">

    <div class="divParametres">
        <label for="prixFinauxQuarantePourcent">Prix +40%</label>
        <input name="prixFinauxQuarantePourcent" type="number" id="prixFinauxQuarantePourcent" value="{{ old('prixFinauxQuarantePourcent') }}" required>
        @error('prixFinauxQuarantePourcent') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="divParametres">
        <label for="prixFinauxCinquantePourcent">Prix +50%</label>
        <input name="prixFinauxCinquantePourcent" type="number" id="prixFinauxCinquantePourcent" value="{{ old('prixFinauxCinquantePourcent') }}" required>
        @error('prixFinauxCinquantePourcent') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

</div>
