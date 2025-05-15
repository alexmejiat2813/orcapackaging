<form id="form-ParamsBase" class="form-to-validate" method="POST">
    @csrf
    <div id="container-clients" class="divParametres">
        <label for="clients"> Choix du client </label>
        <select name="client" id="clients" style="width: 300px;" required>
            <option value="">-- Sélectionner --</option>
            @foreach($clients as $client)
                <option value="{{ $client->Customer_No }}">
                    {{ $client->Customer_Name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="inputNomClient">Nom</label>
            <input name="nomClient" type="text" id="inputNomClient" placeholder="Doe" required>
        </div>
        <div class="divParametres">
            <label for="inputPrenomClient">Prénom</label>
            <input name="prenomClient" type="text" id="inputPrenomClient" placeholder="John" required>
        </div>
        <div class="divParametres">
            <label for="inputEmailClient">Email</label>
            <div class="divHorizontale">
                <input name="emailClient" type="email" id="inputEmailClient" placeholder="john.doe@email.ca" required>
                <span id="validationIconEmail" class="validationIcon"></span>
            </div>
        </div>
        <div class="divParametres">
            <label for="inputTelephoneClient">Téléphone</label>
            <div class="divHorizontale">
                <input name="telephoneClient" type="tel" id="inputTelephoneClient" 
                       pattern="[0-9]{10}" title="0123456789" 
                       placeholder="0123456789" maxlength="10" required>
                <span id="validationIconTel" class="validationIcon"></span>
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