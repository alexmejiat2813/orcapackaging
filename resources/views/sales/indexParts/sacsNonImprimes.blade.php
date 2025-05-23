<form id="form-sacsNonImpr" class="form-to-validate" action="traitement.php" method="POST">

    <div class="divParametres">
        <label for="formatProduit"> Format du produit </label>
        <div class="parametres">
        <label><input type="checkbox" class="formatProduit-checkbox" value="Notion"> Notion</label><br>
        <label><input type="checkbox" class="formatProduit-checkbox" value="Wicket"> Wicket</label><br>
        <label><input type="checkbox" class="formatProduit-checkbox" value="Corde"> Corde</label><br>
        <label><input type="checkbox" class="formatProduit-checkbox" value="Colle"> Colle</label><br>
        <label><input type="checkbox" class="formatProduit-checkbox" value="Poignée"> Poignée</label><br>
        <label><input type="checkbox" class="formatProduit-checkbox" value="Bottom Gousset"> Bottom Gousset</label><br>
        <label><input type="checkbox" class="formatProduit-checkbox" value="Reverse Lip"> Reverse Lip</label><br>
        <!-- Hidden input that holds the combined value -->
        <input type="hidden" name="formatProduit" value="test" id="formatProduit">
        </div>
    </div>

    <div class="divVerticale">
        <h2>Mesures du produit final</h2>
        <div class="parametres">
            <div class="divParametres">
                <label for="largeur">Largeur</label>
                <input name="largeur" type="number" id="largeur" required>
            </div>
            <div class="divParametres">
                <label for="hauteur">Hauteur</label>
                <input name="hauteur" type="number" id="hauteur" required>
            </div>
            <div class="divParametres">
                <label for="poignee">Poignee ou Lip</label>
                <input name="poignee" type="number" id="poignee">
            </div>
            <div class="divParametres">
                <label for="gousset">Gousset</label>
                <input name="gousset" type="number" id="gousset">
            </div>
        </div>
    </div>

    <h1> Materiel Initial </h1>

    <div class="parametres">
        <div class="divParametres">
            <label for="quiVaFournirMateriel">Qui va fournir ? </label>
            <select name="quiVaFournirMateriel" id="quiVaFournirMateriel" required>
                <option value="orca">Orca</option>
                <option value="client">Client</option>
                <option value="sous-traitance">Sous-Traitance</option>
            </select>
        </div>
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
        <div class="divParametres">
            <label for="typeMateriauInitial"> Type du materiau initial </label>
            <select name="typeMateriauInitial" id="typeMateriauInitial" required>
                <option value="standard">Standard</option>
                <option value="heavy-duty">Heavy Duty</option>
                <option value="COEX">COEX</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="formatMateriauInitial"> Format du materiau initial </label>
            <select name="formatMateriauInitial" id="formatMateriauInitial" required>
                <option value="sheeting">Sheeting</option>
                <option value="u-film">U-Film</option>
                <option value="j-film">J-film</option>
            </select>
        </div>
    </div>

    <div class="divVerticale">
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
                <label for="lbParMil">Lb/Mil</label>
                <input name="lbParMil" type="number" id="lbParMil" required>
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

    <h1>Conversion</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="quiVaProduireConversion"> Qui va produire ? </label>
            <select name="quiVaProduireConversion" id="quiVaProduireConversion" required>
                <option value="orca">Orca</option>
                <option value="client">Client</option>
                <option value="sous-traitance">Sous-Traitance</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="sacsParHeure"> Sacs par heure </label>
            <input name="sacsParHeure" type="number" id="sacsParHeure" required>
        </div>
        <div class="divParametres">
            <label for="salaireConversion"> Salaire ($CAD) </label>
            <input name="salaireConversion" type="number" id="salaireConversion" required>
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

    <div class="parametres">
        <div class="divParametres">
            <label for="niveauDifficulte"> Niveau de difficulte </label>
            <select name="niveauDifficulte" id="niveauDifficulte" required>
                <option value="1">1 - Tres simple</option>
                <option value="2">2 - Simple</option>
                <option value="3">3 - Moyen</option>
                <option value="4">4 - Complexe</option>
                <option value="5">5 - Tres complexe</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="typeScellage"> Type de scellage </label>
            <select name="typeScellage" id="typeScellage" required>
                <option value="cote">de cote</option>
                <option value="fond">de fond</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="nbTrousAeration"> Nombre de trous d'aeration </label>
            <input name="nbTrousAeration" type="number" id="nbTrousAeration" required>
        </div>
        <div class="divParametres">
            <label for="diametreTrous"> Diametre des trous </label>
            <select name="diametreTrous" id="diametreTrous" required>
                <option value="na">Ne s'applique pas</option>
                <option value="1/8">1/8"</option>
                <option value="1/4">1/4"</option>
                <option value="3/8">3/8"</option>
                <option value="1/2">1/2"</option>
            </select>
        </div>
        <div class="divParametres">
            <label for="positionTrous"> Position des trous </label>
            <input name="positionTrous" type="text" id="positionTrous" required>
        </div>
    </div>

    <h1>Emballage</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="boitesParPalettes">Boites par palette</label>
            <input name="boitesParPalettes" type="number" id="boitesParPalettes" required>
        </div>
        <div class="divParametres">
            <label for="totalSacsParPalette"> Total de sacs par palettes </label>
            <input name="totalSacsParPalette" type="number" id="totalSacsParPalette" required>
        </div>
    </div>

    <div class="parametres">
        <div class="divParametres">
            <label for="sacsParBoite">Sacs par Boite</label>
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

    <h1>Plaques</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="prixPlaquesFournisseur">Prix des plaques fournisseurs ($CAD)</label>
            <input name="prixPlaquesFournisseur" type="number" id="prixPlaquesFournisseur" required>
        </div>
        <div class="divParametres">
            <label for="prixVenteClientPlaque"> Prix de vente au client ($CAD) </label>
            <input name="prixVenteClientPlaque" type="number" id="prixVenteClientPlaque" required>
        </div>
    </div>

    <h1>Couts totaux de production</h1>
    <div class="parametres">
        <div class="divParametres">
            <label for="coutTotauxProductionPlaques">Cout plaques ($CAD)</label>
            <input name="coutTotauxProductionPlaques" type="number" id="coutTotauxProductionPlaques" required>
        </div>
        <div class="divParametres">
            <label for="coutTotauxProductionPellicule"> Cout pellicule ($CAD) </label>
            <input name="coutTotauxProductionPellicule" type="number" id="coutTotauxProductionPellicule" required>
        </div>
        <div class="divParametres">
            <label for="coutTotauxProductionConversion"> Cout Conversion ($CAD) </label>
            <input name="coutTotauxProductionConversion" type="number" id="coutTotauxProductionConversion" required>
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