<form id="form-tape" class="form-to-validate" action="traitement.php" method="POST">

    <div class="parametres">
        <div class="divParametres">
            <label for="typePellicule"> Type de pellicule </label>
            <select name="typePellicule" id="typePellicule" required>
                <option value="LDPE">Low-Density Polyethylene (LDPE)</option>
                <option value="HDPE">High-Density Polyethylene (HDPE)</option>
                <option value="PP">Polypropylene (PP)</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="couleurPellicule"> Couleur de la pellicule </label>
            <select name="couleurPellicule" id="couleurPellicule" required>
                <option value="claire">Claire</option>
                <option value="noir">Noir</option>
                <option value="blanc">Blanche</option>
            </select>
        </div>
    </div>

    <div class="divVerticale">
        <h2>Mesures</h2>
        <div class="parametres">
            <div class="divParametres">
                <label for="largeur">Largeur</label>
                <input name="largeur" type="number" id="largeur" required>
            </div>
            <div class="divParametres">
                <label for="hauteur">Hauteur</label>
                <input name="hauteur" type="number" id="hauteur" required>
            </div>
        </div>
    </div>

    <div class="divVerticale">
        <h2>Materiel</h2>
        <div class="parametres">
            <div class="divParametres">
                <label for="web">Web</label>
                <input name="web" type="number" id="web" required>
            </div>
            <div class="divParametres">
                <label for="epaisseur">Epaisseur</label>
                <input name="epaisseur" type="number" id="epaisseur" required>
            </div>
            <div class="divParametres">
                <label for="coutParLivre">Cout par livre</label>
                <input name="coutParLivre" type="number" id="coutParLivre" required>
            </div>
            <div class="divParametres">
                <label for="totalPieds">Total de Pieds</label>
                <input name="totalPieds" type="number" id="totalPieds" required>
            </div>
            <div class="divParametres">
                <label for="totalLivres">Total de Livres</label>
                <input name="totalLivres" type="number" id="totalLivres" required>
            </div>
        </div>
    </div>

    <div class="divVerticale">
        <h2>Quantite totale finale</h2>
        <div class="parametres">
            <div class="divParametres">
                <label for="totalPiedsPlusTolerance">Total de Pieds + Tolerance</label>
                <input name="totalPiedsPlusTolerance" type="number" id="totalPiedsPlusTolerance" required>
            </div>
            <div class="divParametres">
                <label for="totalLivresPlusTolerance">Total de Livres + Tolerance</label>
                <input name="totalLivresPlusTolerance" type="number" id="totalLivresPlusTolerance" required>
            </div>
            <div class="divParametres">
                <label for="coutTotal">Cout Total (en $CAD)</label>
                <input name="coutTotal" type="number" id="coutTotal" required>
            </div>
        </div>
    </div>

    <h1> Encre et solvant </h1>

    <div class="parametres">
        <div class="divParametres">
            <label for="typeEncre"> Type d'encre </label>
            <select name="typeEncre" id="typeEncre" required>
                <option value="exterieur"> Exterieur </option>
                <option value="surface"> Surface </option>
                <option value="hercubone"> Hercubone </option>
                <option value="thermoplast"> Thermoplast </option>
                <option value="process"> Process </option>
                <option value="xks"> XKS </option>
            </select>
        </div>
        <div class="divParametres">
            <label for="nbCotes"> Nombre de cotes </label>
            <input name="nbCotes" type="number" id="nbCotes" required>
        </div>
        <div class="divParametres">
            <label for="nbEncres"> Nombre d'encres </label>
            <select name="nbEncres" id="nbEncres" class="nbEncres" required>
                <option value="1"> 1 </option>
                <option value="2"> 2 </option>
            </select>
        </div>
        <div class="divParametres">
            <label for="poucesCarresUnitaire">Pouces Carres unitaire</label>
            <input name="poucesCarresUnitaire" type="number" id="poucesCarresUnitaire">
        </div>
        <div class="divParametres">
            <label for="poucesCarresParQuantiteAProduire">Pouces Carres par Quantite a Produire </label>
            <input name="poucesCarresParQuantiteAProduire" type="number" id="poucesCarresParQuantiteAProduire">
        </div>
        <div class="divParametres">
            <label for="constante"> Constante </label>
            <input name="constante" type="number" id="constante" value="0,00000331767">
        </div>
    </div>

    <h1> Couleurs de base </h1>
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
    <div class="divVerticale">
        <div class="parametres">
            <div class="divParametres">
                <label for="quantiteKGSolvant"> Quantite KG </label>
                <input name="quantiteKGSolvant" type="number" id="quantiteKGSolvant">
            </div>
            <div class="divParametres">
                <label for="coutKGSolvant"> Cout par KG ($CAD) </label>
                <input name="coutKGSolvant" type="number" id="coutKGSolvant">
            </div>
            <div class="divParametres">
                <label for="coutTotalSolvant"> Cout total du solvant ($CAD) </label>
                <input name="coutTotalSolvant" type="number" id="coutTotalSolvant">
            </div>
        </div>
    </div>

    <h1>Impression</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="piedsParHeure"> Pieds par Heure </label>
            <input name="piedsParHeure" type="number" id="piedsParHeure" required>
        </div>
        <div class="divParametres">
            <label for="salaireImpression"> Salaire ($CAD) </label>
            <input name="salaireImpression" type="number" id="salaireImpression" required>
        </div>
    </div>

    <h2>Temps de production</h2>
    <div class="parametres">
        <div class="divParametres">
            <label for="dureeTotaleImpression"> Duree totale d'impression (en minutes) </label>
            <input name="dureeTotaleImpression" type="number" id="dureeTotaleImpression" required>
        </div>
        <div class="divParametres">
            <label for="dureeMontagePlaques"> Montage des plaques (en minutes) </label>
            <input name="dureeMontagePlaques" type="number" id="dureeMontagePlaques" required>
        </div>
        <div class="divParametres">
            <label for="dureeMiseEnTrain"> Mise en train (en minutes) </label>
            <input name="dureeMiseEnTrain" type="number" id="dureeMiseEnTrain" required>
        </div>
        <div class="divParametres">
            <label for="dureeLavage"> Lavage (en minutes) </label>
            <input name="dureeLavage" type="number" id="dureeLavage" required>
        </div>
        <div class="divParametres">
            <label for="tempsTotalProduction"> Temps total (en minutes) </label>
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
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="sacsParBoite">Tape par Boite</label>
            <input name="sacsParBoite" type="number" id="sacsParBoite" required>
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

    <h1>Couts totaux de production</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="coutTotauxProductionPellicule"> Cout pellicule ($CAD) </label>
            <input name="coutTotauxProductionPellicule" type="number" id="coutTotauxProductionPellicule" required>
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
            <label for="quantiteInformative1">Quantite</label>
            <input name="quantiteInformative1" type="number" id="quantiteInformative1" readonly>
        </div>
        <div class="divParametres">
            <label for="prixInformatif1"> $CAD </label>
            <input name="prixInformatif1" type="number" id="prixInformatif1" readonly>
        </div>
    </div>
    <div class="parametres">
        <div class="divParametres">
            <label for="quantiteInformative2">Quantite</label>
            <input name="quantiteInformative2" type="number" id="quantiteInformative2" readonly>
        </div>
        <div class="divParametres">
            <label for="prixInformatif2"> $CAD </label>
            <input name="prixInformatif2" type="number" id="prixInformatif2" readonly>
        </div>
    </div>
    <div class="parametres">
        <div class="divParametres">
            <label for="quantiteInformative3">Quantite</label>
            <input name="quantiteInformative3" type="number" id="quantiteInformative3" readonly>
        </div>
        <div class="divParametres">
            <label for="prixInformatif3"> $CAD </label>
            <input name="prixInformatif3" type="number" id="prixInformatif3" readonly>
        </div>
    </div>

</form>