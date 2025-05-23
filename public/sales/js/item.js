// ON CHANGE type item
function afficherTexte() {
    var choix = document.getElementById("commande").value;
    document.getElementById("zoneSacsImpr").style.display = "none";
    document.getElementById("zoneSacsNonImpr").style.display = "none";
    document.getElementById("zoneRouleaux").style.display = "none";
    document.getElementById("zoneSacsPapier").style.display = "none";
    document.getElementById("zoneTape").style.display = "none";

    if (choix === "sacsImpr") {
        document.getElementById("zoneSacsImpr").style.display = "block";
    } else if (choix === "sacsNonImpr") {
        document.getElementById("zoneSacsNonImpr").style.display = "block";
    } else if (choix === "rouleaux") {
        document.getElementById("zoneRouleaux").style.display = "block";
    } else if (choix === "sacsPapier") {
        document.getElementById("zoneSacsPapier").style.display = "block";
    } else if (choix === "tape") {
        document.getElementById("zoneTape").style.display = "block";
    }
}
afficherTexte();

// GESTION CHECKBOX
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.formatProduit-checkbox');
    const hiddenInput = document.getElementById('formatProduit');
  
    function updateHiddenInput() {
      const selected = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);
      hiddenInput.value = selected.join(', ');
    }
  
    checkboxes.forEach(cb => {
      cb.addEventListener('change', updateHiddenInput);
    });
  
    updateHiddenInput();
});

// COULEURS
const couleurs = [
    { label: "Beige", value: "beige" },
    { label: "Blanc", value: "blanc" },
    { label: "Bleu", value: "bleu" },
    { label: "Cyan", value: "cyan" },
    { label: "Gris", value: "gris" },
    { label: "Jaune", value: "jaune" },
    { label: "Magenta", value: "magenta" },
    { label: "Marron", value: "marron" },
    { label: "Noir", value: "noir" },
    { label: "Or", value: "or" },
    { label: "Orange", value: "orange" },
    { label: "Rouge", value: "rouge" },
    { label: "Vert", value: "vert" },
    { label: "Violet", value: "violet" }
  ];

  function createEncreBlock(index) {
    const div = document.createElement('div');
    div.className = "divVerticale";
    div.id = `divCouleur${index}`;
  
    div.innerHTML = `
      <h2>Encre ${index}</h2>
      <div class="parametres">
        <div class="divParametres">
          <label for="couleur${index}"> Couleur</label>
          <select name="couleur${index}" id="couleur${index}" class="select-couleur" required></select>
        </div>
        <div class="divParametres">
          <label for="surface${index}"> Surface </label>
          <input name="surface${index}" type="number" id="surface${index}">
        </div>
        <div class="divParametres">
          <label for="couverture${index}"> Couverture (%) </label>
          <input name="couverture${index}" type="number" id="couverture${index}">
        </div>
        <div class="divParametres">
          <label for="kg${index}"> KG </label>
          <input name="kg${index}" type="number" id="kg${index}">
        </div>
        <div class="divParametres">
          <label for="coutParKG${index}"> Cout par KG ($CAD) </label>
          <input name="coutParKG${index}" type="number" id="coutParKG${index}">
        </div>
        <div class="divParametres">
          <label for="coutTotalEncre${index}"> Coût total </label>
          <input name="coutTotalEncre${index}" type="number" id="coutTotalEncre${index}">
        </div>
      </div>
    `;
  
    return div;
  }

  function populateColors(select) {
    couleurs.forEach(couleur => {
      const option = document.createElement('option');
      option.value = couleur.value;
      option.textContent = couleur.label;
      select.appendChild(option);
    });
  }

  function renderEncres(nbMax) {
    const containers = document.querySelectorAll(".encresContainer");
  
    containers.forEach(container => {
      container.innerHTML = "";
  
      for (let i = 1; i <= nbMax; i++) {
        const block = createEncreBlock(i);
        block.style.display = i === 1 ? "block" : "none"; // Affiche le 1er par défaut
        container.appendChild(block);
  
        const select = block.querySelector('.select-couleur');
        populateColors(select);
      }
    });
  }
  
  // Appelle initial avec un maximum d'encres (ex: 10)
  const NB_MAX_ENCRES = 5;
  renderEncres(NB_MAX_ENCRES);
  
  // Gestion dynamique de l'affichage
  document.querySelectorAll('.nbEncres').forEach(select => {
    select.addEventListener('change', event => {
      const valeurActuelle = parseInt(event.target.value, 10);
      const form = event.target.closest('form');
      const container = form.querySelector('.encresContainer');
      if (!container) return;
  
      const blocs = container.querySelectorAll('.divVerticale');
      blocs.forEach((bloc, index) => {
        bloc.style.display = index < valeurActuelle ? "block" : "none";
      });
    });
  });

// Pour rajouter des classes pour tous les objets HTML avec un ID

document.querySelectorAll("form").forEach(form => {
  form.querySelectorAll("[id]").forEach(el => {
    el.classList.add(el.id);
  });
});

///////////////////////// GESTION DES NOMBRES (Modification automatique des inputs) //////////////////////////////////////

// 62-66 ??  91 ?? 

// prixVenteClientPlaque vers coutTotauxProductionPlaques 12
document.querySelectorAll(".prixVenteClientPlaque").forEach(element =>
{
  element.addEventListener("input", event => {
    event.target.closest('form').querySelector("#coutTotauxProductionPlaques").value = element.value;
  });
});

// Petite fonction utilitaire locale pour DRY le code
  const attacherListener = (input, handler) => {
    if (input) input.addEventListener("input", handler);
  };

// poucesCarresParSac * prixStickyBagPoucesCarres = prixStickyBagTotal 13 
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputSurface = form.querySelector('[name="poucesCarresParSac"]');
  const inputPrixUnitaire = form.querySelector('[name="prixStickyBagPoucesCarres"]');
  const inputTotal = form.querySelector('[name="prixStickyBagTotal"]');

  if (!inputSurface || !inputPrixUnitaire || !inputTotal) return;

  const calculerTotal = () => {
    const surface = parseFloat(inputSurface.value) || 0;
    const prixUnitaire = parseFloat(inputPrixUnitaire.value) || 0;
    const total = surface * prixUnitaire;
    inputTotal.value = total.toFixed(2);
    inputTotal.dispatchEvent(new Event("input", { bubbles: true }));
  };

  attacherListener(inputSurface, calculerTotal);
  attacherListener(inputPrixUnitaire, calculerTotal);
});

// web * largeur = poucesCarresParSac 14
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputWeb = form.querySelector('[name="web"]');
  const inputLargeur = form.querySelector('[name="largeur"]');
  const inputSurface = form.querySelector('[name="poucesCarresParSac"]');

  if (!inputWeb || !inputLargeur || !inputSurface) return;

  const calculerSurface = () => {
    const web = parseFloat(inputWeb.value) || 0;
    const largeur = parseFloat(inputLargeur.value) || 0;
    const surface = web * largeur;
    inputSurface.value = surface.toFixed(2);
    inputSurface.dispatchEvent(new Event("input", { bubbles: true }));
  };

  attacherListener(inputWeb, calculerSurface);
  attacherListener(inputLargeur, calculerSurface);
});

// quantite / totalSacsParPalette (rouleauxParPalette) = totalPalettes 20      // ARRONDIR
document.getElementById("container-commande").addEventListener("change", event => {
  let form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputSacsParPalette = form.querySelector('[name="totalSacsParPalette"]');
  const inputRouleauxParPalette = form.querySelector('[name="rouleauxParPalettes"]');
  const inputPalettes = form.querySelector('[name="totalPalettes"]');

  if (!inputQuantite || !inputPalettes) return;

  function calculerPalettes() {
    const quantite = parseFloat(inputQuantite.value) || 0;

    // Choisir la valeur palette existante
    var valeurPalette = 1;
    if (inputSacsParPalette) valeurPalette = parseFloat(inputSacsParPalette.value);
    if (inputRouleauxParPalette) valeurPalette = parseFloat(inputRouleauxParPalette.value);

    const total = Math.ceil(quantite / valeurPalette);
    inputPalettes.value = total;
    inputPalettes.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerPalettes);
  if (inputSacsParPalette) inputSacsParPalette.addEventListener("input", calculerPalettes);
  if (inputRouleauxParPalette) inputRouleauxParPalette.addEventListener("input", calculerPalettes);
});


// boitesParPalettes (rouleauxParPalettes) * sacsParBoite (impressionsParRouleaux) = totalSacsParPalette 21    // ARRONDIR
document.getElementById("container-commande").addEventListener("change", event => {
  let form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputBoitesParPalette = form.querySelector('[name="boitesParPalettes"]');
  const inputSacsParBoite = form.querySelector('[name="sacsParBoite"]');
  const inputRouleauxParPalette = form.querySelector('[name="rouleauxParPalettes"]');
  const inputImpressionsParRouleaux = form.querySelector('[name="impressionsParRouleaux"]');
  const inputTotalSacsParPalette = form.querySelector('[name="totalSacsParPalette"]');
  const inputTotalImpressionsParPalette = form.querySelector('[name="totalImpressionsParPalette"]');

  function calculerTotalSacsParPalette() {
    const boites = parseFloat(inputBoitesParPalette.value) || 0;
    const sacs = parseFloat(inputSacsParBoite.value) || 0;
    const total = Math.ceil(boites * sacs);
    if (inputTotalSacsParPalette) {
      inputTotalSacsParPalette.value = total;
      inputTotalSacsParPalette.dispatchEvent(new Event("input", { bubbles: true }));
    }
  }

  function calculerTotalImpressionsParPalette() {
    const rouleaux = parseFloat(inputRouleauxParPalette.value) || 0;
    const impressions = parseFloat(inputImpressionsParRouleaux.value) || 0;
    const total = Math.ceil(rouleaux * impressions);
    if (inputTotalImpressionsParPalette) {
      inputTotalImpressionsParPalette.value = total;
      inputTotalImpressionsParPalette.dispatchEvent(new Event("input", { bubbles: true }));
    }
  }

  function calculerTotalPalette() {
    if (inputBoitesParPalette && inputSacsParBoite) {
      calculerTotalSacsParPalette();
    } else if (inputRouleauxParPalette && inputImpressionsParRouleaux) {
      calculerTotalImpressionsParPalette();
    }
  }

  // Attache les listeners
  if (inputBoitesParPalette) inputBoitesParPalette.addEventListener("input", calculerTotalPalette);
  if (inputSacsParBoite) inputSacsParBoite.addEventListener("input", calculerTotalPalette);
  if (inputRouleauxParPalette) inputRouleauxParPalette.addEventListener("input", calculerTotalPalette);
  if (inputImpressionsParRouleaux) inputImpressionsParRouleaux.addEventListener("input", calculerTotalPalette);
});

// ((coutsFinaux * 1.5) / quantite ) * 1000 = prixFinauxCinquantePourcent 23               // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixFinaux = form.querySelector('[name="prixFinauxCinquantePourcent"]');

  if (!inputCoutsFinaux || !inputQuantite || !inputPrixFinaux) return;

  function calculerPrix() {
    const couts = parseFloat(inputCoutsFinaux.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 1; // éviter division par 0
    const prix = ((couts * 1.5) / quantite) * 1000;
    inputPrixFinaux.value = prix.toFixed(2);
    inputPrixFinaux.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCoutsFinaux.addEventListener("input", calculerPrix);
  inputQuantite.addEventListener("input", calculerPrix);
});

// ((coutsFinaux * 1.4) / quantite ) * 1000 = prixFinauxCinquantePourcent 24               // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixFinaux = form.querySelector('[name="prixFinauxQuarantePourcent"]');

  if (!inputCoutsFinaux || !inputQuantite || !inputPrixFinaux) return;

  function calculerPrix() {
    const couts = parseFloat(inputCoutsFinaux.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 1; // éviter division par 0
    const prix = ((couts * 1.4) / quantite) * 1000;
    inputPrixFinaux.value = prix.toFixed(2);
    inputPrixFinaux.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCoutsFinaux.addEventListener("input", calculerPrix);
  inputQuantite.addEventListener("input", calculerPrix);
});


// ((coutsFinaux * 1.3) / quantite ) * 1000 = prixFinauxCinquantePourcent 25               // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixFinaux = form.querySelector('[name="prixFinauxTrentePourcent"]');

  if (!inputCoutsFinaux || !inputQuantite || !inputPrixFinaux) return;

  function calculerPrix() {
    const couts = parseFloat(inputCoutsFinaux.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 1; // éviter division par 0
    const prix = ((couts * 1.3) / quantite) * 1000;
    inputPrixFinaux.value = prix.toFixed(2);
    inputPrixFinaux.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCoutsFinaux.addEventListener("input", calculerPrix);
  inputQuantite.addEventListener("input", calculerPrix);
});


// ((coutsFinaux * 1.2) / quantite ) * 1000 = prixFinauxCinquantePourcent 26               // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixFinaux = form.querySelector('[name="prixFinauxVingtPourcent"]');

  if (!inputCoutsFinaux || !inputQuantite || !inputPrixFinaux) return;

  function calculerPrix() {
    const couts = parseFloat(inputCoutsFinaux.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 1; // éviter division par 0
    const prix = ((couts * 1.2) / quantite) * 1000;
    inputPrixFinaux.value = prix.toFixed(2);
    inputPrixFinaux.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCoutsFinaux.addEventListener("input", calculerPrix);
  inputQuantite.addEventListener("input", calculerPrix);
});

// ((coutsFinaux * 1.1) / quantite ) * 1000 = prixFinauxCinquantePourcent 27               // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixFinaux = form.querySelector('[name="prixFinauxDixPourcent"]');

  if (!inputCoutsFinaux || !inputQuantite || !inputPrixFinaux) return;

  function calculerPrix() {
    const couts = parseFloat(inputCoutsFinaux.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 1; // éviter division par 0
    const prix = ((couts * 1.1) / quantite) * 1000;
    inputPrixFinaux.value = prix.toFixed(2);
    inputPrixFinaux.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCoutsFinaux.addEventListener("input", calculerPrix);
  inputQuantite.addEventListener("input", calculerPrix);
});

// coutsPlusComission / quantite = prixFinauxUniteAvecProfit 29   // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputCouts = form.querySelector('[name="coutsPlusComission"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixUnite = form.querySelector('[name="prixFinauxUniteAvecProfit"]');

  if (!inputCouts || !inputQuantite || !inputPrixUnite) return;

  function calculerPrixUnite() {
    const couts = parseFloat(inputCouts.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 1; // éviter division par 0
    const prix = couts / quantite;
    inputPrixUnite.value = prix.toFixed(2);
    inputPrixUnite.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCouts.addEventListener("input", calculerPrixUnite);
  inputQuantite.addEventListener("input", calculerPrixUnite);
});

// prixFinauxUniteAvecProfit * 1000 = prixFinauxMilleAvecProfit 28    // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputPrixUnite = form.querySelector('[name="prixFinauxUniteAvecProfit"]');
  const inputPrixMille = form.querySelector('[name="prixFinauxMilleAvecProfit"]');

  if (!inputPrixUnite || !inputPrixMille) return;

  function calculerPrixMille() {
    const prixUnite = parseFloat(inputPrixUnite.value) || 0;
    const prixMille = prixUnite * 1000;
    inputPrixMille.value = prixMille.toFixed(2);
    inputPrixMille.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputPrixUnite.addEventListener("input", calculerPrixMille);
});

// coutsFinaux / quantite = prixFinauxSansProfit 30   // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixSansProfit = form.querySelector('[name="prixFinauxSansProfit"]');

  if (!inputCoutsFinaux || !inputQuantite || !inputPrixSansProfit) return;

  function calculerPrixSansProfit() {
    const couts = parseFloat(inputCoutsFinaux.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 1; // pour éviter la division par 0
    const prix = couts / quantite;
    inputPrixSansProfit.value = prix.toFixed(2);
    inputPrixSansProfit.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCoutsFinaux.addEventListener("input", calculerPrixSansProfit);
  inputQuantite.addEventListener("input", calculerPrixSansProfit);
});


// ((commission / 100) * coutsFinaux) + coutsPlusProfit = coutsPlusComission 31     // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputCommission = document.getElementById("form-inputsCommunItem").querySelector('[name="commission"]');
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputCoutsPlusProfit = form.querySelector('[name="coutsPlusProfit"]');
  const inputCoutsPlusComission = form.querySelector('[name="coutsPlusComission"]');

  if (!inputCommission || !inputCoutsFinaux || !inputCoutsPlusProfit || !inputCoutsPlusComission) return;

  function calculerCoutsPlusComission() {
    const commission = parseFloat(inputCommission.value) || 0;
    const coutsFinaux = parseFloat(inputCoutsFinaux.value) || 0;
    const coutsPlusProfit = parseFloat(inputCoutsPlusProfit.value) || 0;

    const comissionMontant = (commission / 100) * coutsFinaux;
    const total = comissionMontant + coutsPlusProfit;

    inputCoutsPlusComission.value = total.toFixed(2);
    inputCoutsPlusComission.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputCommission.addEventListener("input", calculerCoutsPlusComission);
  inputCoutsFinaux.addEventListener("input", calculerCoutsPlusComission);
  inputCoutsPlusProfit.addEventListener("input", calculerCoutsPlusComission);
});


// (1+(profit/100) * coutsFinaux = coutsPlusProfit 32         // ARRONDIR
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputProfit = document.getElementById("form-inputsCommunItem").querySelector('[name="profit"]');
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputCoutsPlusProfit = form.querySelector('[name="coutsPlusProfit"]');

  if (!inputProfit || !inputCoutsFinaux || !inputCoutsPlusProfit) return;

  function calculerCoutsPlusProfit() {
    const profit = parseFloat(inputProfit.value) || 0;
    const coutsFinaux = parseFloat(inputCoutsFinaux.value) || 0;
    const total = (1 + (profit / 100)) * coutsFinaux;

    inputCoutsPlusProfit.value = total.toFixed(2);
    inputCoutsPlusProfit.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputProfit.addEventListener("input", calculerCoutsPlusProfit);
  inputCoutsFinaux.addEventListener("input", calculerCoutsPlusProfit);
});



// coutTotauxProductionPlaques + coutTotauxProductionPellicule + coutTotauxProductionEncre + coutTotauxProductionSolvant + coutTotauxProductionImpression +
// coutTotauxProductionConversion + coutTotauxProductionEmballage + coutTotauxProductionLivraison + coutTotauxProductionEntrepot = coutsFinaux  36
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputPlaques = form.querySelector('[name="coutTotauxProductionPlaques"]');
  const inputPellicule = form.querySelector('[name="coutTotauxProductionPellicule"]');
  const inputEncre = form.querySelector('[name="coutTotauxProductionEncre"]');
  const inputSolvant = form.querySelector('[name="coutTotauxProductionSolvant"]');
  const inputImpression = form.querySelector('[name="coutTotauxProductionImpression"]');
  const inputConversion = form.querySelector('[name="coutTotauxProductionConversion"]');
  const inputEmballage = form.querySelector('[name="coutTotauxProductionEmballage"]');
  const inputLivraison = form.querySelector('[name="coutTotauxProductionLivraison"]');
  const inputEntrepot = form.querySelector('[name="coutTotauxProductionEntrepot"]');
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');

  if (!inputPlaques || !inputPellicule || !inputEncre || !inputSolvant || !inputImpression ||
      !inputConversion || !inputEmballage || !inputLivraison || !inputEntrepot || !inputCoutsFinaux) return;

  function calculerCoutsFinaux() {
    const total = [
      inputPlaques, inputPellicule, inputEncre, inputSolvant, inputImpression,
      inputConversion, inputEmballage, inputLivraison, inputEntrepot
    ].reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

    inputCoutsFinaux.value = total.toFixed(2);
    inputCoutsFinaux.dispatchEvent(new Event("input", { bubbles: true }));
  }

  [
    inputPlaques, inputPellicule, inputEncre, inputSolvant, inputImpression,
    inputConversion, inputEmballage, inputLivraison, inputEntrepot
  ].forEach(input => input.addEventListener("input", calculerCoutsFinaux));
});


// dureeTotaleConversion + dureeMontageConversion + dureeMenageConversion = tempsTotalConversion 39
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputDureeTotale = form.querySelector('[name="dureeTotaleConversion"]');
  const inputMontage = form.querySelector('[name="dureeMontageConversion"]');
  const inputMenage = form.querySelector('[name="dureeMenageConversion"]');
  const inputTempsTotal = form.querySelector('[name="tempsTotalConversion"]');

  if (!inputDureeTotale || !inputMontage || !inputMenage || !inputTempsTotal) return;

  function calculerTempsTotal() {
    const total = 
      (parseFloat(inputDureeTotale.value) || 0) +
      (parseFloat(inputMontage.value) || 0) +
      (parseFloat(inputMenage.value) || 0);

    inputTempsTotal.value = total;
    inputTempsTotal.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputDureeTotale.addEventListener("input", calculerTempsTotal);
  inputMontage.addEventListener("input", calculerTempsTotal);
  inputMenage.addEventListener("input", calculerTempsTotal);
});


// dureeTotaleConversion * 0.05 = dureeMenageConversion 40
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputDureeTotale = form.querySelector('[name="dureeTotaleConversion"]');
  const inputDureeMenage = form.querySelector('[name="dureeMenageConversion"]');

  if (!inputDureeTotale || !inputDureeMenage) return;

  function calculerDureeMenage() {
    const dureeTotale = parseFloat(inputDureeTotale.value) || 0;
    const menage = dureeTotale * 0.05;
    inputDureeMenage.value = menage.toFixed(2);
    inputDureeMenage.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputDureeTotale.addEventListener("input", calculerDureeMenage);
});

// dureeTotaleConversion * 0.05 = dureeMontageConversion 45
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputDureeTotale = form.querySelector('[name="dureeTotaleConversion"]');
  const inputDureeMontage = form.querySelector('[name="dureeMontageConversion"]');

  if (!inputDureeTotale || !inputDureeMontage) return;

  function calculerDureeMontage() {
    const dureeTotale = parseFloat(inputDureeTotale.value) || 0;
    const montage = dureeTotale * 0.05;
    inputDureeMontage.value = montage.toFixed(2);
    inputDureeMontage.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputDureeTotale.addEventListener("input", calculerDureeMontage);
});


// (quantite / sacsParHeure) * 60 = dureeTotaleConversion 46
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputSacsParHeure = form.querySelector('[name="sacsParHeure"]');
  const inputDureeTotale = form.querySelector('[name="dureeTotaleConversion"]');

  if (!inputQuantite || !inputSacsParHeure || !inputDureeTotale) return;

  function calculerDureeTotale() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const sacsParHeure = parseFloat(inputSacsParHeure.value) || 1; // éviter division par 0
    const duree = (quantite / sacsParHeure) * 60;
    inputDureeTotale.value = duree.toFixed(2);
    inputDureeTotale.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerDureeTotale);
  inputSacsParHeure.addEventListener("input", calculerDureeTotale);
});


// quantite / sacsParBoite = nbBoites 54
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputSacsParBoite = form.querySelector('[name="sacsParBoite"]');
  const inputNbBoites = form.querySelector('[name="nbBoites"]');

  if (!inputQuantite || !inputSacsParBoite || !inputNbBoites) return;

  function calculerNbBoites() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const sacsParBoite = parseFloat(inputSacsParBoite.value) || 1;
    const nb = quantite / sacsParBoite;
    inputNbBoites.value = Math.ceil(nb); // on arrondit vers le haut pour ne pas rater de boîte
    inputNbBoites.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerNbBoites);
  inputSacsParBoite.addEventListener("input", calculerNbBoites);
});

// (nbBoites * coutBoite) + (totalPalettes * coutPalette) = coutTotauxProductionEmballage 56
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputNbBoites = form.querySelector('[name="nbBoites"]');
  const inputCoutBoite = form.querySelector('[name="coutBoite"]');
  const inputTotalPalettes = form.querySelector('[name="totalPalettes"]');
  const inputCoutPalette = form.querySelector('[name="coutPalette"]');
  const inputCoutEmballage = form.querySelector('[name="coutTotauxProductionEmballage"]');

  if (!inputNbBoites || !inputCoutBoite || !inputTotalPalettes || !inputCoutPalette || !inputCoutEmballage) return;

  function calculerCoutEmballage() {
    const nbBoites = parseFloat(inputNbBoites.value) || 0;
    const coutBoite = parseFloat(inputCoutBoite.value) || 0;
    const totalPalettes = parseFloat(inputTotalPalettes.value) || 0;
    const coutPalette = parseFloat(inputCoutPalette.value) || 0;

    const total = (nbBoites * coutBoite) + (totalPalettes * coutPalette);
    inputCoutEmballage.value = total.toFixed(2);
    inputCoutEmballage.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputNbBoites.addEventListener("input", calculerCoutEmballage);
  inputCoutBoite.addEventListener("input", calculerCoutEmballage);
  inputTotalPalettes.addEventListener("input", calculerCoutEmballage);
  inputCoutPalette.addEventListener("input", calculerCoutEmballage);
});

// (salaireConversion * tempsTotalConversion) / 60 = coutTotauxProductionConversion 57  // BIZARRE
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputSalaire = form.querySelector('[name="salaireConversion"]');
  const inputTemps = form.querySelector('[name="tempsTotalConversion"]');
  const inputCout = form.querySelector('[name="coutTotauxProductionConversion"]');

  if (!inputSalaire || !inputTemps || !inputCout) return;

  function calculerCoutConversion() {
    const salaire = parseFloat(inputSalaire.value) || 0;
    const temps = parseFloat(inputTemps.value) || 0;
    const total = (salaire * temps) / 60;
    inputCout.value = total.toFixed(2);
    inputCout.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputSalaire.addEventListener("input", calculerCoutConversion);
  inputTemps.addEventListener("input", calculerCoutConversion);
});

// (salaireImpression * tempsTotalProduction) / 60 = coutTotauxProductionImpression 58  // BIZARRE
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputSalaire = form.querySelector('[name="salaireImpression"]');
  const inputTemps = form.querySelector('[name="tempsTotalProduction"]');
  const inputCout = form.querySelector('[name="coutTotauxProductionImpression"]');

  if (!inputSalaire || !inputTemps || !inputCout) return;

  function calculerCoutImpression() {
    const salaire = parseFloat(inputSalaire.value) || 0;
    const temps = parseFloat(inputTemps.value) || 0;
    const total = (salaire * temps) / 60;
    inputCout.value = total.toFixed(2);
    inputCout.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputSalaire.addEventListener("input", calculerCoutImpression);
  inputTemps.addEventListener("input", calculerCoutImpression);
});

// coutTotalSolvant = coutTotauxProductionSolvant 59
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputSource = form.querySelector('[name="coutTotalSolvant"]');
  const inputDestination = form.querySelector('[name="coutTotauxProductionSolvant"]');

  if (!inputSource || !inputDestination) return;

  function copierCoutSolvant() {
    const value = parseFloat(inputSource.value) || 0;
    inputDestination.value = value.toFixed(2);
    inputDestination.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputSource.addEventListener("input", copierCoutSolvant);
});

// coutTotalEncre = coutTotauxProductionEncre 60
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputSource = form.querySelector('[name="coutTotalEncre"]');
  const inputDestination = form.querySelector('[name="coutTotauxProductionEncre"]');

  if (!inputSource || !inputDestination) return;

  function copierCoutEncre() {
    const value = parseFloat(inputSource.value) || 0;
    inputDestination.value = value.toFixed(2);
    inputDestination.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputSource.addEventListener("input", copierCoutEncre);
});

// coutTotal = coutTotauxProductionPellicule 61
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputSource = form.querySelector('[name="coutTotal"]');
  const inputDestination = form.querySelector('[name="coutTotauxProductionPellicule"]');

  if (!inputSource || !inputDestination) return;

  function copierCoutPellicule() {
    const value = parseFloat(inputSource.value) || 0;
    inputDestination.value = value.toFixed(2);
    inputDestination.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputSource.addEventListener("input", copierCoutPellicule);
});

// (coutTotalEncre1 + CoutTotalEncre2 + ....) = coutTotalEncre 67
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputNbEncres = form.querySelector('[name="nbEncres"]');
  const inputTotalEncre = form.querySelector('[name="coutTotalEncre"]');

  if (!inputNbEncres || !inputTotalEncre) return;

  function calculerTotalEncre() {
    const nb = parseInt(inputNbEncres.value) || 1;
    let total = 0;

    for (let i = 1; i <= nb; i++) {
      const input = form.querySelector(`[name="coutTotalEncre${i}"]`);
      total += input ? parseFloat(input.value) || 0 : 0;
    }

    inputTotalEncre.value = total.toFixed(2);
    inputTotalEncre.dispatchEvent(new Event("input", { bubbles: true }));
  }

  // Attacher les écouteurs
  inputNbEncres.addEventListener("input", calculerTotalEncre);
  for (let i = 1; i <= 5; i++) {
    const input = form.querySelector(`[name="coutTotalEncre${i}"]`);
    if (input) input.addEventListener("input", calculerTotalEncre);
  }
});

// (kg1 + kg2 + ....) = totalKilosEncre 68
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputTotalKg = form.querySelector('[name="totalKilosEncre"]');
  const inputNbEncres = form.querySelector('[name="nbEncres"]');

  if (!inputTotalKg || !inputNbEncres) return;

  function calculerTotalKilos() {
    const nb = parseInt(inputNbEncres.value) || 1;
    let total = 0;

    for (let i = 1; i <= nb; i++) {
      const input = form.querySelector(`[name="kg${i}"]`);
      total += input ? parseFloat(input.value) || 0 : 0;
    }

    inputTotalKg.value = total.toFixed(2);
    inputTotalKg.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputNbEncres.addEventListener("input", calculerTotalKilos); // 👈 ici c'était manquant

  for (let i = 1; i <= 5; i++) {
    const input = form.querySelector(`[name="kg${i}"]`);
    if (input) input.addEventListener("input", calculerTotalKilos);
  }
});

// dureeTotaleImpression + dureeMontagePlaques + dureeMiseEnTrain + dureeLavage = tempsTotalProduction 69
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputImpression = form.querySelector('[name="dureeTotaleImpression"]');
  const inputMontage = form.querySelector('[name="dureeMontagePlaques"]');
  const inputMiseEnTrain = form.querySelector('[name="dureeMiseEnTrain"]');
  const inputLavage = form.querySelector('[name="dureeLavage"]');
  const inputTotal = form.querySelector('[name="tempsTotalProduction"]');

  if (!inputImpression || !inputMontage || !inputMiseEnTrain || !inputLavage || !inputTotal) return;

  function calculerTempsTotalProduction() {
    const impression = parseFloat(inputImpression.value) || 0;
    const montage = parseFloat(inputMontage.value) || 0;
    const miseEnTrain = parseFloat(inputMiseEnTrain.value) || 0;
    const lavage = parseFloat(inputLavage.value) || 0;

    const total = impression + montage + miseEnTrain + lavage;
    inputTotal.value = total.toFixed(2);
    inputTotal.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputImpression.addEventListener("input", calculerTempsTotalProduction);
  inputMontage.addEventListener("input", calculerTempsTotalProduction);
  inputMiseEnTrain.addEventListener("input", calculerTempsTotalProduction);
  inputLavage.addEventListener("input", calculerTempsTotalProduction);
});

// nbEncres * 0.33 = dureeLavage 70
// nbEncres * 0.5 = dureeMiseEnTrain 71
// nbEncres * 0.25 = dureeMontagePlaques 72
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputNbEncres = form.querySelector('[name="nbEncres"]');
  const inputLavage = form.querySelector('[name="dureeLavage"]');
  const inputMiseEnTrain = form.querySelector('[name="dureeMiseEnTrain"]');
  const inputMontagePlaques = form.querySelector('[name="dureeMontagePlaques"]');

  if (!inputNbEncres || !inputLavage || !inputMiseEnTrain || !inputMontagePlaques) return;

  function calculerDureesImpression() {
    const nbEncres = parseFloat(inputNbEncres.value) || 0;

    inputLavage.value = (nbEncres * (1/3) * 60).toFixed(2);
    inputMiseEnTrain.value = (nbEncres * 0.5 * 60).toFixed(2);
    inputMontagePlaques.value = (nbEncres * 0.25 * 60).toFixed(2);

    inputLavage.dispatchEvent(new Event("input", { bubbles: true }));
    inputMiseEnTrain.dispatchEvent(new Event("input", { bubbles: true }));
    inputMontagePlaques.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputNbEncres.addEventListener("input", calculerDureesImpression);
});

// totalPiedsPlusTolerance / piedsParHeure = dureeTotaleImpression 73
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputTotalPieds = form.querySelector('[name="totalPiedsPlusTolerance"]');
  const inputPiedsParHeure = form.querySelector('[name="piedsParHeure"]');
  const inputDureeImpression = form.querySelector('[name="dureeTotaleImpression"]');

  if (!inputTotalPieds || !inputPiedsParHeure || !inputDureeImpression) return;

  function calculerDureeImpression() {
    const pieds = parseFloat(inputTotalPieds.value) || 0;
    const vitesse = parseFloat(inputPiedsParHeure.value) || 1; // éviter division par zéro
    const duree = (pieds / vitesse) * 60;
    inputDureeImpression.value = duree.toFixed(2);
    inputDureeImpression.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputTotalPieds.addEventListener("input", calculerDureeImpression);
  inputPiedsParHeure.addEventListener("input", calculerDureeImpression);
});

// Pour chaque encre : (surface(num) / (largeur * web)) * 100 = couverture(num)  74-78
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputLargeur = form.querySelector('[name="largeur"]');
  const inputWeb = form.querySelector('[name="web"]');

  if (!inputLargeur || !inputWeb) return;

  function calculerCouvertures() {
    const largeur = parseFloat(inputLargeur.value) || 0;
    const web = parseFloat(inputWeb.value) || 0;
    const surfaceTotale = largeur * web;

    if (surfaceTotale === 0) return;

    for (let i = 1; i <= 5; i++) {
      const inputSurface = form.querySelector(`[name="surface${i}"]`);
      const inputCouverture = form.querySelector(`[name="couverture${i}"]`);

      if (inputSurface && inputCouverture) {
        const surface = parseFloat(inputSurface.value) || 0;
        const couverture = (surface / surfaceTotale) * 100;
        inputCouverture.value = couverture.toFixed(2);
        inputCouverture.dispatchEvent(new Event("input", { bubbles: true }));
      }
    }
  }

  // Écouteurs globaux
  inputLargeur.addEventListener("input", calculerCouvertures);
  inputWeb.addEventListener("input", calculerCouvertures);
  for (let i = 1; i <= 5; i++) {
    const input = form.querySelector(`[name="surface${i}"]`);
    if (input) input.addEventListener("input", calculerCouvertures);
  }
});

// (kg1 + kg2 + ....)*0.3 = quantiteKGSolvant 79
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputSolvant = form.querySelector('[name="quantiteKGSolvant"]');

  if (!inputSolvant) return;

  function calculerQuantiteSolvant() {
    let totalKg = 0;
    for (let i = 1; i <= 5; i++) {
      const inputKg = form.querySelector(`[name="kg${i}"]`);
      totalKg += inputKg ? parseFloat(inputKg.value) || 0 : 0;
    }

    const solvant = totalKg * 0.3;
    inputSolvant.value = solvant.toFixed(2);
    inputSolvant.dispatchEvent(new Event("input", { bubbles: true }));
  }

  // Ajout des écouteurs
  for (let i = 1; i <= 5; i++) {
    const inputKg = form.querySelector(`[name="kg${i}"]`);
    if (inputKg) inputKg.addEventListener("input", calculerQuantiteSolvant);
  }
});


// quantiteKGSolvant * coutKGSolvant = coutTotalSolvant 80
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputQuantiteSolvant = form.querySelector('[name="quantiteKGSolvant"]');
  const inputCoutParKg = form.querySelector('[name="coutKGSolvant"]');
  const inputCoutTotal = form.querySelector('[name="coutTotalSolvant"]');

  if (!inputQuantiteSolvant || !inputCoutParKg || !inputCoutTotal) return;

  function calculerCoutSolvant() {
    const quantite = parseFloat(inputQuantiteSolvant.value) || 0;
    const coutParKg = parseFloat(inputCoutParKg.value) || 0;
    const total = quantite * coutParKg;
    inputCoutTotal.value = total.toFixed(2);
    inputCoutTotal.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantiteSolvant.addEventListener("input", calculerCoutSolvant);
  inputCoutParKg.addEventListener("input", calculerCoutSolvant);
});


// kg(num) * coutParKG(num) = coutTotalEncre(num) 81-85
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  function calculerCoutsEncres() {
    for (let i = 1; i <= 5; i++) {
      const inputKg = form.querySelector(`[name="kg${i}"]`);
      const inputCoutParKg = form.querySelector(`[name="coutParKG${i}"]`);
      const inputCoutTotal = form.querySelector(`[name="coutTotalEncre${i}"]`);

      if (inputKg && inputCoutParKg && inputCoutTotal) {
        const kg = parseFloat(inputKg.value) || 0;
        const coutParKg = parseFloat(inputCoutParKg.value) || 0;
        const total = kg * coutParKg;
        inputCoutTotal.value = total.toFixed(2);
        inputCoutTotal.dispatchEvent(new Event("input", { bubbles: true }));
      }
    }
  }

  // Écouteurs sur tous les champs kgX et coutParKGX
  for (let i = 1; i <= 5; i++) {
    const inputKg = form.querySelector(`[name="kg${i}"]`);
    const inputCoutParKg = form.querySelector(`[name="coutParKG${i}"]`);

    if (inputKg) inputKg.addEventListener("input", calculerCoutsEncres);
    if (inputCoutParKg) inputCoutParKg.addEventListener("input", calculerCoutsEncres);
  }
});


// (constante * poucesCarresParQuantiteAProduire) * (couverture(num) / 100) = kg(num)  86-90
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputConstante = form.querySelector('[name="constante"]');
  const inputSurfaceTotale = form.querySelector('[name="poucesCarresParQuantiteAProduire"]');

  if (!inputConstante || !inputSurfaceTotale) return;

  function calculerKgs() {
    const constante = parseFloat(inputConstante.value) || 0;
    const surface = parseFloat(inputSurfaceTotale.value) || 0;
    const base = constante * surface;

    for (let i = 1; i <= 5; i++) {
      const inputCouverture = form.querySelector(`[name="couverture${i}"]`);
      const inputKg = form.querySelector(`[name="kg${i}"]`);

      if (inputCouverture && inputKg) {
        const couverture = parseFloat(inputCouverture.value) || 0;
        const kg = base * (couverture / 100);
        inputKg.value = kg.toFixed(2);
        inputKg.dispatchEvent(new Event("input", { bubbles: true }));
      }
    }
  }

  // Écouteurs
  inputConstante.addEventListener("input", calculerKgs);
  inputSurfaceTotale.addEventListener("input", calculerKgs);
  for (let i = 1; i <= 5; i++) {
    const inputCouverture = form.querySelector(`[name="couverture${i}"]`);
    if (inputCouverture) inputCouverture.addEventListener("input", calculerKgs);
  }
});

// quantite * (1 + (tolerance / 100)) * largeur * web = poucesCarresParQuantiteAProduire 91
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputTolerance = document.getElementById("form-inputsCommunItem").querySelector('[name="tolerance"]');
  const inputLargeur = form.querySelector('[name="largeur"]');
  const inputWeb = form.querySelector('[name="web"]');
  const inputSurface = form.querySelector('[name="poucesCarresParQuantiteAProduire"]');

  if (!inputQuantite || !inputTolerance || !inputLargeur || !inputWeb || !inputSurface) return;

  function calculerSurfaceTotale() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const tolerance = parseFloat(inputTolerance.value) || 0;
    const largeur = parseFloat(inputLargeur.value) || 0;
    const web = parseFloat(inputWeb.value) || 0;

    const surface = quantite * (1 + (tolerance / 100)) * largeur * web;
    inputSurface.value = surface.toFixed(2);
    inputSurface.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerSurfaceTotale);
  inputTolerance.addEventListener("input", calculerSurfaceTotale);
  inputLargeur.addEventListener("input", calculerSurfaceTotale);
  inputWeb.addEventListener("input", calculerSurfaceTotale);
});


// totalLivresPlusTolerance * coutParLivre = coutTotal 94
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputLivres = form.querySelector('[name="totalLivresPlusTolerance"]');
  const inputCoutParLivre = form.querySelector('[name="coutParLivre"]');
  const inputCoutTotal = form.querySelector('[name="coutTotal"]');

  if (!inputLivres || !inputCoutParLivre || !inputCoutTotal) return;

  function calculerCoutTotal() {
    const livres = parseFloat(inputLivres.value) || 0;
    const cout = parseFloat(inputCoutParLivre.value) || 0;
    const total = livres * cout;
    inputCoutTotal.value = total.toFixed(2);
    inputCoutTotal.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputLivres.addEventListener("input", calculerCoutTotal);
  inputCoutParLivre.addEventListener("input", calculerCoutTotal);
});

// totalLivres * (1 + (tolerance/100)) = totalLivresPlusTolerance 95
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputTotalLivres = form.querySelector('[name="totalLivres"]');
  const inputTolerance = document.getElementById("form-inputsCommunItem").querySelector('[name="tolerance"]');
  const inputTotalAvecTolerance = form.querySelector('[name="totalLivresPlusTolerance"]');

  if (!inputTotalLivres || !inputTolerance || !inputTotalAvecTolerance) return;

  function calculerLivresAvecTolerance() {
    const total = parseFloat(inputTotalLivres.value) || 0;
    const tol = parseFloat(inputTolerance.value) || 0;
    const totalAvecTol = total * (1 + (tol / 100));
    inputTotalAvecTolerance.value = totalAvecTol.toFixed(2);
    inputTotalAvecTolerance.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputTotalLivres.addEventListener("input", calculerLivresAvecTolerance);
  inputTolerance.addEventListener("input", calculerLivresAvecTolerance);
});

// totalPieds * (1 + (tolerance/100)) = totalPiedsPlusTolerance 96
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputTotalPieds = form.querySelector('[name="totalPieds"]');
  const inputTolerance = document.getElementById("form-inputsCommunItem").querySelector('[name="tolerance"]');
  const inputTotalAvecTolerance = form.querySelector('[name="totalPiedsPlusTolerance"]');

  if (!inputTotalPieds || !inputTolerance || !inputTotalAvecTolerance) return;

  function calculerPiedsAvecTolerance() {
    const pieds = parseFloat(inputTotalPieds.value) || 0;
    const tol = parseFloat(inputTolerance.value) || 0;
    const totalAvecTol = pieds * (1 + (tol / 100));
    inputTotalAvecTolerance.value = totalAvecTol.toFixed(2);
    inputTotalAvecTolerance.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputTotalPieds.addEventListener("input", calculerPiedsAvecTolerance);
  inputTolerance.addEventListener("input", calculerPiedsAvecTolerance);
});

// ((web/2) * largeur * hauteur ) / typeMateriauInitial = lbParMil 97 ?
// (((web/2) * largeur * hauteur ) / typeMateriauInitial) * (quantite/1000) = totalLivres 98
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');

  const inputWeb = form.querySelector('[name="web"]');
  const inputLargeur = form.querySelector('[name="largeur"]');
  const inputHauteur = form.querySelector('[name="hauteur"]');
  const selectType = form.querySelector('[name="typeMateriauInitial"]');

  const inputLbParMil = form.querySelector('[name="lbParMil"]');
  const inputTotalLivres = form.querySelector('[name="totalLivres"]');

  if (!inputWeb || !inputLargeur || !inputHauteur || !selectType || !inputLbParMil || !inputTotalLivres || !inputQuantite) return;

  function calculerLbParMilEtTotalLivres() {
    const web = parseFloat(inputWeb.value) || 0;
    const largeur = parseFloat(inputLargeur.value) || 0;
    const hauteur = parseFloat(inputHauteur.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 0;

    const typeText = selectType.value.trim().toLowerCase();
    const type = typeText === "standard" ? 15 : 14;

    const lbParMil = ((web / 2) * largeur * hauteur) / type;
    inputLbParMil.value = lbParMil.toFixed(2);
    inputLbParMil.dispatchEvent(new Event("input", { bubbles: true }));

    const totalLivres = lbParMil * (quantite / 1000);
    inputTotalLivres.value = totalLivres.toFixed(2);
    inputTotalLivres.dispatchEvent(new Event("input", { bubbles: true }));
  }

  // Écouteurs communs
  inputWeb.addEventListener("input", calculerLbParMilEtTotalLivres);
  inputLargeur.addEventListener("input", calculerLbParMilEtTotalLivres);
  inputHauteur.addEventListener("input", calculerLbParMilEtTotalLivres);
  inputQuantite.addEventListener("input", calculerLbParMilEtTotalLivres);
  selectType.addEventListener("input", calculerLbParMilEtTotalLivres);
});

// (quantite * largeur) / 12 = totalPieds 99 ?
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputLargeur = form.querySelector('[name="largeur"]');
  const inputTotalPieds = form.querySelector('[name="totalPieds"]');

  if (!inputQuantite || !inputLargeur || !inputTotalPieds) return;

  function calculerTotalPieds() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const largeur = parseFloat(inputLargeur.value) || 0;
    const total = (quantite * largeur) / 12;
    inputTotalPieds.value = total.toFixed(2);
    inputTotalPieds.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerTotalPieds);
  inputLargeur.addEventListener("input", calculerTotalPieds);
});


// (hauteur*2) + poignee + gousset = web 100
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputHauteur = form.querySelector('[name="hauteur"]');
  const inputPoignee = form.querySelector('[name="poignee"]');
  const inputGousset = form.querySelector('[name="gousset"]');
  const inputWeb = form.querySelector('[name="web"]');

  if (!inputHauteur || !inputPoignee || !inputGousset || !inputWeb) return;

  function calculerWeb() {
    const hauteur = parseFloat(inputHauteur.value) || 0;
    const poignee = parseFloat(inputPoignee.value) || 0;
    const gousset = parseFloat(inputGousset.value) || 0;

    const web = (hauteur * 2) + poignee + gousset;
    inputWeb.value = web.toFixed(2);
    inputWeb.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputHauteur.addEventListener("input", calculerWeb);
  inputPoignee.addEventListener("input", calculerWeb);
  inputGousset.addEventListener("input", calculerWeb);
});

// Gestion variation de prix selon quantite
document.getElementById("container-commande").addEventListener("change", () => {
  const form = document.getElementById("form-" + document.getElementById("commande").value);

  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrix = document.getElementById("form-inputsCommunItem").querySelector('[name="prix"]');
  const inputQuantiteInformative1 = form.querySelector('[name="quantiteInformative1"]');
  const inputQuantiteInformative2 = form.querySelector('[name="quantiteInformative2"]');
  const inputQuantiteInformative3 = form.querySelector('[name="quantiteInformative3"]');
  const inputPrixInformatif1 = form.querySelector('[name="prixInformatif1"]');
  const inputPrixInformatif2 = form.querySelector('[name="prixInformatif2"]');
  const inputPrixInformatif3 = form.querySelector('[name="prixInformatif3"]');

  if (!inputQuantite || !inputPrix || !inputQuantiteInformative1 || !inputQuantiteInformative2 || !inputQuantiteInformative3
    || !inputPrixInformatif1 || !inputPrixInformatif2 || !inputPrixInformatif3
  ) return;

  function calculerQuantiteInformative() {
    inputQuantiteInformative1.value = inputQuantite.value;
    inputQuantiteInformative2.value = inputQuantite.value / 2;
    inputQuantiteInformative3.value = inputQuantite.value * 2;
  }

  function calculerPrixInformatif() {
    inputPrixInformatif1.value = inputPrix.value;
    inputPrixInformatif2.value = inputPrix.value / 2;
    inputPrixInformatif3.value = inputPrix.value * 2;
  }

  inputQuantite.addEventListener("input", calculerQuantiteInformative);
  inputPrix.addEventListener("input", calculerPrixInformatif);
});

