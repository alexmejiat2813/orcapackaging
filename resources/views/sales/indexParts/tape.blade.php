<form id="form-tape" class="form-to-validate" action="traitement.php" method="POST">

    <h2>Mesures du materiau</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="largeur">Largeur (mm)</label>
            <select name="largeur" id="largeur" required>
                <option value=""></option>
                <option value="96">96</option>
                <option value="144">144</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="longueur">Longueur (m)</label>
            <select name="longueur" id="longueur" required>
                <option value=""></option>
                <option value="914">914</option>
            </select>
        </div>
    </div>
    <h2>Mesures du produit fini</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="largeurTape">Largeur (mm)</label>
            <select name="largeurTape" id="largeurTape" required>
                <option value=""></option>
                <option value="48">48</option>
                <option value="72">72</option>
            </select>
        </div>
        <div class="divParametres">
        <label for="longueurTape">Longueur (m)</label>
        <input list="longueurTape-list" name="longueurTape" id="longueurTape" required>
        <datalist id="longueurTape-list">
            <option value="100">
        </datalist>
    </div>
    <div class="divParametres">
        <label for="repetitionsTape">Repetitions (po)</label>
        <input list="repetitionsTape-list" name="repetitionsTape" id="repetitionsTape" required>
        <datalist id="repetitionsTape-list">
            <option value="13.02">
            <option value="13.76">
            <option value="14.50">
            <option value="15.24">
            <option value="15.99">
        </datalist>
    </div>
    </div>
    <h2>Total</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="totalMateriauTape"> Total Rouleaux Materiau </label>
            <input name="totalMateriauTape" type="number" id="totalMateriauTape" readonly>
        </div>
        <div class="divParametres">
            <label for="prixMateriau"> Prix Materiau </label>
            <input name="prixMateriau" type="number" id="prixMateriau" readonly>
        </div>
        <div class="divParametres">
            <label for="totalRepetitionsTape"> Total de repetitions </label>
            <input name="totalRepetitionsTape" type="number" id="totalRepetitionsTape" readonly>
        </div>
        <div class="divParametres">
            <label for="nbPistes"> Nombre de pistes </label>
            <input name="nbPistes" type="number" id="nbPistes" readonly>
        </div>
    </div>


    <h1> Encre et solvant </h1>

    <div class="parametres">
        <div class="divParametres">
            <label for="nbEncres"> Nombre d'encres </label>
            <select name="nbEncres" id="nbEncres" class="nbEncres" required>
                <option value="1"> 1 </option>
                <option value="2"> 2 </option>
            </select>
        </div>
    </div>

    <h1> Couleurs de base </h1>
    <!-- Couleurs ajoutees selon le nombre besoin dans le javascript item.js -->
    <div class="encresContainer">
    </div>   

    <div class="parametres">
        <div class="divParametres">
            <label for="totalKilosEncre"> Total de kilos d'encre necessaire </label>
            <input name="totalKilosEncre" type="number" id="totalKilosEncre">
        </div>
        <div class="divParametres">
            <label for="coutTotalEncre"> Cout total de l'encre ($CAD) </label>
            <input name="coutTotalEncre" type="number" id="coutTotalEncre">
        </div>
    </div>

    <h2>Solvant</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="quantiteKGSolvant"> Quantite KG </label>
            <input name="quantiteKGSolvant" type="number" id="quantiteKGSolvant">
        </div>
        <div class="divParametres">
            <label for="coutKGSolvant"> Cout par KG ($CAD) </label>
            <input name="coutKGSolvant" type="number" id="coutKGSolvant" value="4.2">
        </div>
        <div class="divParametres">
            <label for="coutTotalSolvant"> Cout total du solvant ($CAD) </label>
            <input name="coutTotalSolvant" type="number" id="coutTotalSolvant">
        </div>
    </div>

    <h1>Impression</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="quiVaImprimer"> Qui va imprimer ? </label>
            <select name="quiVaImprimer" id="quiVaImprimer" required>
                <option value="orca">Orca</option>
                <option value="client">Client</option>
                <option value="sous-traitance">Sous-Traitance</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="metresParHeureTape"> Metres par Heure </label>
            <input name="metresParHeureTape" type="number" id="metresParHeureTape" required>
        </div>
        <div class="divParametres">
            <label for="salaireImpression"> Salaire ($CAD) </label>
            <input name="salaireImpression" type="number" id="salaireImpression" required>
        </div>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="commentaireImpression">Commentaire d'impression</label>
            <textarea id="commentaireImpression" name="commentaireImpression" rows="6" cols="40"></textarea>
        </div>
    </div>

    <h2>Temps de production</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="dureeTotaleImpression"> Duree totale d'impression  </label>
            <input name="dureeTotaleImpression" type="number" id="dureeTotaleImpression" required>
        </div>
        <div class="divParametres">
            <label for="dureeMontagePlaques"> Montage des plaques  </label>
            <input name="dureeMontagePlaques" type="number" id="dureeMontagePlaques" required>
        </div>
        <div class="divParametres">
            <label for="dureeMiseEnTrain"> Mise en train  </label>
            <input name="dureeMiseEnTrain" type="number" id="dureeMiseEnTrain" required>
        </div>
        <div class="divParametres">
            <label for="dureeLavage"> Lavage  </label>
            <input name="dureeLavage" type="number" id="dureeLavage" required>
        </div>
        <div class="divParametres">
            <label for="tempsTotalProduction"> Temps total  </label>
            <input name="tempsTotalProduction" type="number" id="tempsTotalProduction" required>
        </div>
    </div>

    <h1>Emballage</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="boitesParPalettes">Boites par palette</label>
            <input name="boitesParPalettes" type="number" id="boitesParPalettes" required>
        </div>
        <div class="divParametres">
            <label for="totalTapeParPalette"> Total de tape par palettes </label>
            <input name="totalTapeParPalette" type="number" id="totalTapeParPalette" required>
        </div>
        <div class="divParametres">
            <label for="typePalette"> Type de palette </label>
            <select name="typePalette" id="typePalette" required>
                <option value="23543">40x48</option>
                <option value="27505">48x48"</option>
            </select>
        </div>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="tapeParBoite">Tape par Boite</label>
            <input name="tapeParBoite" type="number" id="tapeParBoite" required>
        </div>
        <div class="divParametres">
            <label for="nbBoites"> Nombre de Boites </label>
            <input name="nbBoites" type="number" id="nbBoites" required>
        </div>
        <div class="divParametres">
            <label for="coutBoite"> Cout de la boite ($CAD) </label>
            <input name="coutBoite" type="number" id="coutBoite" value="0.65" required>
        </div>
        <div class="divParametres">
            <label for="totalPalettes"> Total de palettes </label>
            <input name="totalPalettes" type="number" id="totalPalettes" required>
        </div>
        <div class="divParametres">
            <label for="coutPalette"> Cout de la palette ($CAD) </label>
            <input name="coutPalette" type="number" id="coutPalette" required>
        </div>
    </div>

    <h1>Livraison</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="quiVaLivrer"> Qui va livrer ? </label>
            <select name="quiVaLivrer" id="quiVaLivrer" required>
                <option value="Orca">Orca</option>
                <option value="Client">Client</option>
                <option value="Pick Up">Pick-Up</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="transport"> Quel transporteur ? </label>
            <select name="transport" id="transport" class="form-select" style="width: 300px;" required>
                <option value=""> Aucun </option>
                @foreach($transports as $transport)
                    <option value="{{ $transport->Transp_Description }}">
                        {{ $transport->Transp_Description }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="divParametres">
            <label for="compteTransporteur"> Compte transporteur </label>
            <input name="compteTransporteur" type="text" id="compteTransporteur">
        </div>
        <div class="divParametres">
            <label for="prixLivraison"> Prix de Livraison ($CAD) </label>
            <input name="prixLivraison" type="number" id="prixLivraison" required>
        </div>
    </div>

    <h1>Couts totaux de production</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="coutTotauxProductionMateriau"> Cout materiau ($CAD) </label>
            <input name="coutTotauxProductionMateriau" type="number" id="coutTotauxProductionMateriau" required>
        </div>
        <div class="divParametres">
            <label for="coutTotauxProductionEncre"> Cout encre ($CAD) </label>
            <input name="coutTotauxProductionEncre" type="number" id="coutTotauxProductionEncre" required>
        </div>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="coutTotauxProductionSolvant">Cout Solvant ($CAD)</label>
            <input name="coutTotauxProductionSolvant" type="number" id="coutTotauxProductionSolvant" required>
        </div>
        <div class="divParametres">
            <label for="coutTotauxProductionImpression"> Cout Impression ($CAD) </label>
            <input name="coutTotauxProductionImpression" type="number" id="coutTotauxProductionImpression" required>
        </div>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="coutTotauxProductionEmballage">Cout Emballage ($CAD)</label>
            <input name="coutTotauxProductionEmballage" type="number" id="coutTotauxProductionEmballage" required>
        </div>
        <div class="divParametres">
            <label for="coutTotauxProductionLivraison"> Cout Livraison ($CAD) </label>
            <input name="coutTotauxProductionLivraison" type="number" id="coutTotauxProductionLivraison" required>
        </div>
        <div class="divParametres">
            <label for="coutTotauxProductionEntrepot"> Cout Entrepot ($CAD) </label>
            <input name="coutTotauxProductionEntrepot" type="number" id="coutTotauxProductionEntrepot" required>
        </div>
    </div>

    <h1>Prix finaux</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="coutsFinaux">Couts Finaux</label>
            <input name="coutsFinaux" type="number" id="coutsFinaux" required>
        </div>
        <div class="divParametres">
            <label for="coutsPlusProfit"> Couts plus profits </label>
            <input name="coutsPlusProfit" type="number" id="coutsPlusProfit" required>
        </div>
        <div class="divParametres">
            <label for="coutsPlusComission"> Couts Plus Comission </label>
            <input name="coutsPlusComission" type="number" id="coutsPlusComission" required>
        </div>
    </div>
    <div class="parametres">
        <div class="divParametres">
            <label for="prixFinauxSansProfit">Prix sans profit ou commission</label>
            <input name="prixFinauxSansProfit" type="number" id="prixFinauxSansProfit" required>
        </div>
        <div class="divParametres">
            <label for="prixFinauxUniteAvecProfit"> Prix par unite avec profit et commission </label>
            <input name="prixFinauxUniteAvecProfit" type="number" id="prixFinauxUniteAvecProfit" required>
        </div>
    </div>
    <div class="parametres">
        <div class="divParametres">
            <label for="prixFinauxMilleAvecProfit"> Prix par mille unites </label>
            <input name="prixFinauxMilleAvecProfit" type="number" id="prixFinauxMilleAvecProfit" required>
        </div>
        <div class="divParametres">
            <label for="prixFinauxDixPourcent"> Prix plus 10% </label>
            <input name="prixFinauxDixPourcent" type="number" id="prixFinauxDixPourcent" required>
        </div>
        <div class="divParametres">
            <label for="prixFinauxVingtPourcent"> Prix plus 20% </label>
            <input name="prixFinauxVingtPourcent" type="number" id="prixFinauxVingtPourcent" required>
        </div>
    </div>
    <div class="parametres">
        <div class="divParametres">
            <label for="prixFinauxTrentePourcent"> Prix plus 30% </label>
            <input name="prixFinauxTrentePourcent" type="number" id="prixFinauxTrentePourcent" required>
        </div>
        <div class="divParametres">
            <label for="prixFinauxQuarantePourcent"> Prix plus 40% </label>
            <input name="prixFinauxQuarantePourcent" type="number" id="prixFinauxQuarantePourcent" required>
        </div>
        <div class="divParametres">
            <label for="prixFinauxCinquantePourcent"> Prix plus 50% </label>
            <input name="prixFinauxCinquantePourcent" type="number" id="prixFinauxCinquantePourcent" required>
        </div>
    </div>

    <h1>Variation prix selon quantite</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="quantiteInformative">Quantite initiale</label>
            <input name="quantiteInformative" type="number" id="quantiteInformative" readonly>
        </div>
        <div class="divParametres">
            <label for="prixInformatif"> Prix par mille ($CAD) </label>
            <input name="prixInformatif" type="number" id="prixInformatif" readonly>
        </div>
    </div>
    <div class="parametres">
        <div class="divVerticale">
            <label for="nvlQuantite"> Nouvelle quantite </label>
            <input name="nvlQuantite" type="number" id="nvlQuantite" placeholder="ex : 1000, 50000 etc...">
            <button type="button" name="calculAutreQuantite" id="calculAutreQuantite">
              Estimer avec la nouvelle quantité
            </button>
        </div>
    </div>
    <div class="parametres">
        <div class="divParametres">
            <label for="nvPrix"> Prix calculee avec la nouvelle quantite </label>
            <input name="nvPrix" type="number" id="nvPrix" readonly>
        </div>
    </div>

</form>