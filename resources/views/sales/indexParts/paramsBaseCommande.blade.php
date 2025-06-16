<form id="form-ParamsBase" class="form-to-validate" method="POST">
    @csrf
    <div class="parametres">
        <div id="container-clients" class="divParametres">
            <label for="clients"> Choix du client </label>
            <select name="clients" id="clients" style="width: 300px;" required>
                <option value="-1"> Prospect ou Inconnu </option>
                @foreach($clients as $client)
                    <option value="{{ $client->Customer_No }}">
                        {{ $client->Customer_Name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div id="container-assets" class="divParametres">
            <label for="assets"> Nom du client </label>
            <select name="assets" id="assets" style="width: 300px;" required>
                <option value="-1"> Prospect ou Inconnu </option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->Asset_ID }}">
                        {{ $asset->Asset_FName }}  {{ $asset->Asset_Name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="client-inconnu" class="parametres">
        <div class="divParametres">
            <label for="inputNomClient">Nom</label>
            <input name="nomClient" type="text" id="inputNomClient" placeholder="Doe">
        </div>
        <div class="divParametres">
            <label for="inputPrenomClient">Prénom</label>
            <input name="prenomClient" type="text" id="inputPrenomClient" placeholder="John">
        </div>
        <div class="divParametres">
            <label for="inputEmailClient">Email</label>
            <div class="divHorizontale">
                <input name="emailClient" type="email" id="inputEmailClient" placeholder="john.doe@email.ca">
                <label id="validationIconEmail" class="validationIcon"></label>
            </div>
        </div>
        <div class="divParametres">
            <label for="inputTelephoneClient">Téléphone</label>
            <div class="divHorizontale">
                <input name="telephoneClient" type="tel" id="inputTelephoneClient" 
                       pattern="[0-9]{10}" title="0123456789" 
                       placeholder="0123456789" maxlength="10">
                <label id="validationIconTel" class="validationIcon"></label>       
            </div>
        </div>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="inputNomTravail">Nom du travail</label>
            <input name="nomTravail" type="text" id="inputNomTravail" required>
        </div>
        <div class="divParametres">
            <label for="inputDateLivraison">Date de livraison souhaitée</label>
            <input name="dateLivraisonSouhaitee" type="date" id="inputDateLivraison" required>
        </div>
    </div>
</form>