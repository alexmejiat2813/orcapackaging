<form id="form-sacsNonImpr" class="form-to-validate" action="traitement.php" method="POST">

    <div class="divParametres">
        <h2>Format du produit</h2>
        <div class="row g-2 w-100"> <!-- g-2 = gap vertical et horizontal -->
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input formatProduit-checkbox" value="Notion"> Notion
                </label>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input formatProduit-checkbox" value="Wicket"> Wicket
                </label>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input formatProduit-checkbox" value="Corde"> Corde
                </label>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input formatProduit-checkbox" value="Colle"> Colle
                </label>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input formatProduit-checkbox" value="Poignée"> Poignée
                </label>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input formatProduit-checkbox" value="Bottom Gousset"> Bottom Gousset
                </label>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input formatProduit-checkbox" value="Reverse Lip"> Reverse Lip
                </label>
            </div>
    
            <!-- Hidden input -->
            <input type="hidden" name="formatProduit" value="test" id="formatProduit">
        </div>
    </div>

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
            <label for="poucesCarresParQuantiteAProduire">Pouces Carres par Quantite a Produire </label>
            <input name="poucesCarresParQuantiteAProduire" type="number" id="poucesCarresParQuantiteAProduire">
        </div>
        <div class="divParametres">
            <label for="coutTotal">Cout Total (en $CAD)</label>
            <input name="coutTotal" type="number" id="coutTotal" required>
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
            <label for="dureeTotaleConversion"> Duree Totale de Conversion  </label>
            <input name="dureeTotaleConversion" type="number" id="dureeTotaleConversion" required>
        </div>
        <div class="divParametres">
            <label for="dureeMontageConversion"> Montage  </label>
            <input name="dureeMontageConversion" type="number" id="dureeMontageConversion" required>
        </div>
        <div class="divParametres">
            <label for="dureeMenageConversion"> Menage  </label>
            <input name="dureeMenageConversion" type="number" id="dureeMenageConversion" required>
        </div>
        <div class="divParametres">
            <label for="tempsTotalConversion"> Temps Total  </label>
            <input name="tempsTotalConversion" type="number" id="tempsTotalConversion" required>
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
            <input name="nbTrousAeration" type="number" id="nbTrousAeration" value="0" required>
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
            <input name="positionTrous" type="text" id="positionTrous">
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