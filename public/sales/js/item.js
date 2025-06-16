// Gestion refresh page
window.addEventListener('load', function () {

    const forms = document.querySelectorAll('form[id^="form-"]');
    forms.forEach(form => {
        form.reset(); 
    });

    // Reset du hiddenInput pour les checkbox
    document.getElementById('formatProduit').value = '';

    const selectCommande = document.getElementById('commande');
        if (selectCommande) {
            // Déclenche manuellement l'événement 'change'
            const event = new Event('change', { bubbles: true });
            selectCommande.dispatchEvent(event);
        }

    setTimeout(() => window.scrollTo(0, 0), 50);
});

function alertBeforeUnload(e) {
        // Affiche une alerte simple, le message est généré automatiquement par le navigateur
        e.preventDefault();
        e.returnValue = '';
    }

    // Active la protection
    window.addEventListener("beforeunload", alertBeforeUnload);

    // Pour les liens internes, confirmation explicite
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (e) {
            const confirmation = confirm("Êtes-vous sûr de vouloir quitter cette page ?");
            if (!confirmation) {
                e.preventDefault();
            } else {
                // Supprime aussi beforeunload si on clique volontairement
                window.removeEventListener("beforeunload", alertBeforeUnload);
            }
        });
    });

// ON CHANGE type item
function afficherTexte() {
    var choix = document.getElementById("commande").value;
    document.getElementById("zoneSacsImpr").style.display = "none";
    document.getElementById("zoneSacsNonImpr").style.display = "none";
    document.getElementById("zoneRouleaux").style.display = "none";
    document.getElementById("zoneSacsPapier").style.display = "none";
    document.getElementById("zoneTape").style.display = "none";

    var section = null;
    if (choix === "sacsImpr") {
        section = document.getElementById("zoneSacsImpr");
        section.style.display = "block";
        section.offsetHeight;
        section.querySelector('[name="nbEncres"]').dispatchEvent(new Event("change", { bubbles: true }));
    } else if (choix === "sacsNonImpr") {
        section = document.getElementById("zoneSacsNonImpr");
        section.style.display = "block";
        section.offsetHeight; 
    } else if (choix === "rouleaux") {
        section = document.getElementById("zoneRouleaux");
        section.style.display = "block";
        section.offsetHeight;
        section.querySelector('[name="nbEncres"]').dispatchEvent(new Event("change", { bubbles: true })); 
    } else if (choix === "sacsPapier") {
        section = document.getElementById("zoneSacsPapier");
        section.style.display = "block";
        section.offsetHeight; 
        section.querySelector('[name="nbEncres"]').dispatchEvent(new Event("change", { bubbles: true }));
    } else if (choix === "tape") {
        section = document.getElementById("zoneTape");
        section.style.display = "block";
        section.offsetHeight;
        section.querySelector('[name="nbEncres"]').dispatchEvent(new Event("change", { bubbles: true })); 
    }
}
afficherTexte();

// COULEURS
const couleurs = [
    { label: "- Choississez une couleur -", value: "" },
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
          <select name="couleur${index}" id="couleur${index}" class="select-couleur"></select>
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

// Pour rajouter des classes pour tous les objets HTML avec un ID et pour les checkboxes

document.querySelectorAll("form").forEach(form => {
  form.querySelectorAll("[id]").forEach(el => {
    el.classList.add(el.id);
  });
  // GESTION CHECKBOX
  form.addEventListener('change', function (event) {
    if (event.target.classList.contains('formatProduit-checkbox')) {
      const checkboxes = form.querySelectorAll('.formatProduit-checkbox');
      const hiddenInput = form.querySelector('#formatProduit');

      const selected = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value.trim());

      hiddenInput.value = Array.from(new Set(selected)).join(', ');
    }
  });
});

///////////////////////// GESTION DES NOMBRES (Modification automatique des inputs) //////////////////////////////////////

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

// Calcul nombre palettes necessaires
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputQuantite = document.getElementById("form-inputsCommunItem")?.querySelector('[name="quantite"]');
  const inputSacsParPalette = form.querySelector('[name="totalSacsParPalette"]');
  const inputRouleauxParPalette = form.querySelector('[name="rouleauxParPalettes"]');
  const inputImpressionsParPalettes = form.querySelector('[name="totalImpressionsParPalette"]');
  const inputTapeParPalette = form.querySelector('[name="totalTapeParPalette"]');
  const inputPalettes = form.querySelector('[name="totalPalettes"]');

  if (!inputQuantite || !inputPalettes) return;

  const calculerPalettes = () => {
    const quantite = parseFloat(inputQuantite.value) || 0;

    let valeurPalette = 1;
    if (inputSacsParPalette) valeurPalette = parseFloat(inputSacsParPalette.value);
    if (inputRouleauxParPalette) valeurPalette = parseFloat(inputRouleauxParPalette.value);
    if (inputTapeParPalette) valeurPalette = parseFloat(inputTapeParPalette.value);
    
    let total = null;
    if (inputSacsParPalette || inputTapeParPalette) total = Math.ceil(quantite / valeurPalette);
    if (inputRouleauxParPalette) total = Math.ceil(quantite / inputImpressionsParPalettes.value);
    inputPalettes.value = total;
    inputPalettes.dispatchEvent(new Event("input", { bubbles: true }));
  };

  attacherListener(inputQuantite, calculerPalettes);
  if (inputSacsParPalette) attacherListener(inputSacsParPalette, calculerPalettes);
  if (inputRouleauxParPalette) {
    attacherListener(inputRouleauxParPalette, calculerPalettes);
    attacherListener(inputImpressionsParPalettes, calculerPalettes);
  }
  if (inputTapeParPalette) attacherListener(inputTapeParPalette, calculerPalettes);
});

// boitesParPalettes (rouleauxParPalettes) * sacsParBoite (impressionsParRouleaux) = totalSacsParPalette 21    // ARRONDIR
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputBoitesParPalette = form.querySelector('[name="boitesParPalettes"]');
  const inputSacsParBoite = form.querySelector('[name="sacsParBoite"]');
  const inputRouleauxParPalette = form.querySelector('[name="rouleauxParPalettes"]');
  const inputImpressionsParRouleaux = form.querySelector('[name="impressionsParRouleaux"]');
  const inputTotalTapeParPalette = form.querySelector('[name="totalTapeParPalette"]');
  const inputTapeParBoite = form.querySelector('[name="tapeParBoite"]');
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

  function calculerTotalTapeParPalette() {
    const boites = parseFloat(inputBoitesParPalette.value) || 0;
    const tapeParBoite = parseFloat(inputTapeParBoite.value) || 0;
    const total = Math.ceil(boites * tapeParBoite);
    if (inputTotalTapeParPalette) {
      inputTotalTapeParPalette.value = total;
      inputTotalTapeParPalette.dispatchEvent(new Event("input", { bubbles: true }));
    }
  }

  function calculerTotalPalette() {
    if (inputBoitesParPalette && inputSacsParBoite) {
      calculerTotalSacsParPalette();
    }
    if (inputRouleauxParPalette && inputImpressionsParRouleaux) {
      calculerTotalImpressionsParPalette();
    }
    if (inputBoitesParPalette && inputTapeParBoite) {
      calculerTotalTapeParPalette();
    }
  }

  // Attache les listeners
  if (inputBoitesParPalette) inputBoitesParPalette.addEventListener("input", calculerTotalPalette);
  if (inputSacsParBoite) inputSacsParBoite.addEventListener("input", calculerTotalPalette);
  if (inputRouleauxParPalette) inputRouleauxParPalette.addEventListener("input", calculerTotalPalette);
  if (inputImpressionsParRouleaux) inputImpressionsParRouleaux.addEventListener("input", calculerTotalPalette);
  if (inputTapeParBoite) inputTapeParBoite.addEventListener("input", calculerTotalPalette);
});

// ((coutsFinaux * 1.5) / quantite ) * 1000 = prixFinauxCinquantePourcent 23               // ARRONDIR
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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

// (coutTotauxProductionPlaques + coutTotauxProductionPellicule + coutTotauxProductionEncre + coutTotauxProductionSolvant + coutTotauxProductionImpression +
// coutTotauxProductionConversion + coutTotauxProductionEmballage + coutTotauxProductionLivraison + coutTotauxProductionEntrepot) + frais_admin = coutsFinaux  36
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputCoutsFinaux = form.querySelector('[name="coutsFinaux"]');
  const inputFraisAdmin = document.getElementById("form-inputsCommunItem").querySelector('[name="frais_admin"]');

  if (!inputCoutsFinaux) return;

  const champs = [
    'coutTotauxProductionPlaques',
    'coutTotauxProductionPellicule',
    'coutTotauxProductionEncre',
    'coutTotauxProductionSolvant',
    'coutTotauxProductionImpression',
    'coutTotauxProductionConversion',
    'coutTotauxProductionEmballage',
    'coutTotauxProductionLivraison',
    'coutTotauxProductionEntrepot',
    'coutTotauxProductionMateriau'
  ];

  function calculerCoutsFinaux() {
    const total = champs.reduce((somme, nom) => {
      const input = form.querySelector(`[name="${nom}"]`);
      return somme + (input ? parseFloat(input.value) || 0 : 0);
    }, 0);
    const frais_admin = parseFloat(inputFraisAdmin.value);

    inputCoutsFinaux.value = (total + frais_admin).toFixed(2);
    inputCoutsFinaux.dispatchEvent(new Event("input", { bubbles: true }));
  }

  champs.forEach(nom => {
    const input = form.querySelector(`[name="${nom}"]`);
    if (input) input.addEventListener("input", calculerCoutsFinaux);
  });
  inputFraisAdmin.addEventListener("input", calculerCoutsFinaux);
});

// dureeTotaleConversion + dureeMontageConversion + dureeMenageConversion = tempsTotalConversion 39
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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


// (quantite / sacsParHeure) = dureeTotaleConversion 46
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputSacsParHeure = form.querySelector('[name="sacsParHeure"]');
  const inputDureeTotale = form.querySelector('[name="dureeTotaleConversion"]');

  if (!inputQuantite || !inputSacsParHeure || !inputDureeTotale) return;

  function calculerDureeTotale() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const sacsParHeure = parseFloat(inputSacsParHeure.value) || 1; // éviter division par 0
    const duree = (quantite / sacsParHeure);
    inputDureeTotale.value = duree.toFixed(2);
    inputDureeTotale.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerDureeTotale);
  inputSacsParHeure.addEventListener("input", calculerDureeTotale);
});


// quantite / sacsParBoite = nbBoites (avec tape aussi) 54
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputSacsParBoite = form.querySelector('[name="sacsParBoite"]');
  const inputTapeParBoite = form.querySelector('[name="tapeParBoite"]');
  const inputNbBoites = form.querySelector('[name="nbBoites"]');

  if (!inputQuantite || !inputNbBoites) return;

  function calculerNbBoites() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const sacsParBoite = parseFloat(inputSacsParBoite?.value) || 0;
    const tapeParBoite = parseFloat(inputTapeParBoite?.value) || 0;

    let nb = 0;

    if (sacsParBoite > 0) {
      nb = quantite / sacsParBoite;
    } else if (tapeParBoite > 0) {
      nb = quantite / tapeParBoite;
    }

    inputNbBoites.value = Math.ceil(nb);
    inputNbBoites.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerNbBoites);
  if (inputSacsParBoite) inputSacsParBoite.addEventListener("input", calculerNbBoites);
  if (inputTapeParBoite) inputTapeParBoite.addEventListener("input", calculerNbBoites);
});

// (salaireConversion * tempsTotalConversion) = coutTotauxProductionConversion 57  // BIZARRE
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputSalaire = form.querySelector('[name="salaireConversion"]');
  const inputTemps = form.querySelector('[name="tempsTotalConversion"]');
  const inputCout = form.querySelector('[name="coutTotauxProductionConversion"]');

  if (!inputSalaire || !inputTemps || !inputCout) return;

  function calculerCoutConversion() {
    const salaire = parseFloat(inputSalaire.value) || 0;
    const temps = parseFloat(inputTemps.value) || 0;
    const total = (salaire * temps);
    inputCout.value = total.toFixed(2);
    inputCout.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputSalaire.addEventListener("input", calculerCoutConversion);
  inputTemps.addEventListener("input", calculerCoutConversion);
});

// (salaireImpression * tempsTotalProduction) = coutTotauxProductionImpression 58  // BIZARRE
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputSalaire = form.querySelector('[name="salaireImpression"]');
  const inputTemps = form.querySelector('[name="tempsTotalProduction"]');
  const inputCout = form.querySelector('[name="coutTotauxProductionImpression"]');

  if (!inputSalaire || !inputTemps || !inputCout) return;

  function calculerCoutImpression() {
    const salaire = parseFloat(inputSalaire.value) || 0;
    const temps = parseFloat(inputTemps.value) || 0;
    const total = (salaire * temps);
    inputCout.value = total.toFixed(2);
    inputCout.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputSalaire.addEventListener("input", calculerCoutImpression);
  inputTemps.addEventListener("input", calculerCoutImpression);
});

// coutTotalSolvant = coutTotauxProductionSolvant 59
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputNbEncres = form.querySelector('[name="nbEncres"]');
  const inputLavage = form.querySelector('[name="dureeLavage"]');
  const inputMiseEnTrain = form.querySelector('[name="dureeMiseEnTrain"]');
  const inputMontagePlaques = form.querySelector('[name="dureeMontagePlaques"]');

  if (!inputNbEncres || !inputLavage || !inputMiseEnTrain || !inputMontagePlaques) return;

  function calculerDureesImpression() {
    const nbEncres = parseFloat(inputNbEncres.value) || 0;

    inputLavage.value = (nbEncres * (1/3)).toFixed(2);
    inputMiseEnTrain.value = (nbEncres * 0.5).toFixed(2);
    inputMontagePlaques.value = (nbEncres * 0.25).toFixed(2);

    inputLavage.dispatchEvent(new Event("input", { bubbles: true }));
    inputMiseEnTrain.dispatchEvent(new Event("input", { bubbles: true }));
    inputMontagePlaques.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputNbEncres.addEventListener("change", calculerDureesImpression);
  inputNbEncres.dispatchEvent(new Event("change", { bubbles: true }));
});

// (totalPiedsPlusTolerance / piedsParHeure) = dureeTotaleImpression 73
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputTotalPieds = form.querySelector('[name="totalPiedsPlusTolerance"]');
  const inputPiedsParHeure = form.querySelector('[name="piedsParHeure"]');
  const inputDureeImpression = form.querySelector('[name="dureeTotaleImpression"]');

  if (!inputTotalPieds || !inputPiedsParHeure || !inputDureeImpression) return;

  function calculerDureeImpression() {
    const pieds = parseFloat(inputTotalPieds.value) || 0;
    const vitesse = parseFloat(inputPiedsParHeure.value) || 1; // éviter division par zéro
    const duree = (pieds / vitesse);
    inputDureeImpression.value = duree.toFixed(1);
    inputDureeImpression.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputTotalPieds.addEventListener("input", calculerDureeImpression);
  inputPiedsParHeure.addEventListener("input", calculerDureeImpression);
});

// Pour chaque encre : (surface(num) / (largeur * web)) * 100 = couverture(num)  74-78
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
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


// (0.00000331767 * poucesCarresParQuantiteAProduire) * (couverture(num) / 100) = kg(num)  86-90
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputSurfaceTotale = form.querySelector('[name="poucesCarresParQuantiteAProduire"]');

  if (!inputSurfaceTotale) return;

  function calculerKgs() {
    const surface = parseFloat(inputSurfaceTotale.value) || 0;
    const base = 0.00000331767 * surface;

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
  inputSurfaceTotale.addEventListener("input", calculerKgs);
  for (let i = 1; i <= 5; i++) {
    const inputCouverture = form.querySelector(`[name="couverture${i}"]`);
    if (inputCouverture) inputCouverture.addEventListener("input", calculerKgs);
  }
});

// quantite * (1 + (tolerance / 100)) * largeur * web = poucesCarresParQuantiteAProduire 91
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
    inputSurface.value = Math.ceil(surface);
    inputSurface.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerSurfaceTotale);
  inputTolerance.addEventListener("input", calculerSurfaceTotale);
  inputLargeur.addEventListener("input", calculerSurfaceTotale);
  inputWeb.addEventListener("input", calculerSurfaceTotale);
});


// totalLivresPlusTolerance * coutParLivre = coutTotal 94
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputLivres = form.querySelector('[name="totalLivresPlusTolerance"]');
  const inputCoutParLivre = form.querySelector('[name="coutParLivre"]');
  const inputCoutTotal = form.querySelector('[name="coutTotal"]');

  if (!inputLivres || !inputCoutParLivre || !inputCoutTotal) return;

  function calculerCoutTotal() {
    const livres = parseFloat(inputLivres.value) || 0;
    const cout = parseFloat(inputCoutParLivre.value) || 0;
    const total = livres * cout;
    inputCoutTotal.value = Math.ceil(total);
    inputCoutTotal.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputLivres.addEventListener("input", calculerCoutTotal);
  inputCoutParLivre.addEventListener("input", calculerCoutTotal);
});

// totalLivres * (1 + (tolerance/100)) = totalLivresPlusTolerance 95
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputTotalLivres = form.querySelector('[name="totalLivres"]');
  const inputTolerance = document.getElementById("form-inputsCommunItem").querySelector('[name="tolerance"]');
  const inputTotalAvecTolerance = form.querySelector('[name="totalLivresPlusTolerance"]');

  if (!inputTotalLivres || !inputTolerance || !inputTotalAvecTolerance) return;

  function calculerLivresAvecTolerance() {
    const total = parseFloat(inputTotalLivres.value) || 0;
    const tol = parseFloat(inputTolerance.value) || 0;
    const totalAvecTol = total * (1 + (tol / 100));
    inputTotalAvecTolerance.value = Math.ceil(totalAvecTol);
    inputTotalAvecTolerance.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputTotalLivres.addEventListener("input", calculerLivresAvecTolerance);
  inputTolerance.addEventListener("input", calculerLivresAvecTolerance);
});

// totalPieds * (1 + (tolerance/100)) = totalPiedsPlusTolerance 96
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputTotalPieds = form.querySelector('[name="totalPieds"]');
  const inputTolerance = document.getElementById("form-inputsCommunItem").querySelector('[name="tolerance"]');
  const inputTotalAvecTolerance = form.querySelector('[name="totalPiedsPlusTolerance"]');

  if (!inputTotalPieds || !inputTolerance || !inputTotalAvecTolerance) return;

  function calculerPiedsAvecTolerance() {
    const pieds = parseFloat(inputTotalPieds.value) || 0;
    const tol = parseFloat(inputTolerance.value) || 0;
    const totalAvecTol = pieds * (1 + (tol / 100));
    inputTotalAvecTolerance.value = Math.ceil(totalAvecTol);
    inputTotalAvecTolerance.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputTotalPieds.addEventListener("input", calculerPiedsAvecTolerance);
  inputTolerance.addEventListener("input", calculerPiedsAvecTolerance);
});

// ((web/2) * largeur * epaisseur ) / typeMateriauInitial = lbParMil 97 ?
// (((web/2) * largeur * epaisseur ) / typeMateriauInitial) * (quantite/1000) = totalLivres 98
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');

  const inputWeb = form.querySelector('[name="web"]');
  const inputLargeur = form.querySelector('[name="largeur"]');
  const inputEpaisseur = form.querySelector('[name="epaisseur"]');
  const selectType = form.querySelector('[name="typeMateriauInitial"]');

  const inputLbParMil = form.querySelector('[name="lbParMil"]');
  const inputTotalLivres = form.querySelector('[name="totalLivres"]');

  if (!inputWeb || !inputLargeur || !selectType || !inputLbParMil || !inputTotalLivres || !inputQuantite) return;

  function calculerLbParMilEtTotalLivres() {
    const web = parseFloat(inputWeb.value) || 0;
    const largeur = parseFloat(inputLargeur.value) || 0;
    const epaisseur = parseFloat(inputEpaisseur.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 0;

    const typeText = selectType.value.trim().toLowerCase();
    const type = typeText === "standard" ? 15 : 14;

    const lbParMil = ((web / 2) * largeur * epaisseur ) / type;
    inputLbParMil.value = Math.ceil(lbParMil);
    inputLbParMil.dispatchEvent(new Event("input", { bubbles: true }));

    const totalLivres = lbParMil * (quantite / 1000);
    inputTotalLivres.value = Math.ceil(totalLivres);
    inputTotalLivres.dispatchEvent(new Event("input", { bubbles: true }));
  }

  // Écouteurs communs
  inputWeb.addEventListener("input", calculerLbParMilEtTotalLivres);
  inputLargeur.addEventListener("input", calculerLbParMilEtTotalLivres);
  inputEpaisseur.addEventListener("input", calculerLbParMilEtTotalLivres);
  inputQuantite.addEventListener("input", calculerLbParMilEtTotalLivres);
  selectType.addEventListener("input", calculerLbParMilEtTotalLivres);
});

// (quantite * largeur) / 12 = totalPieds 99 ?
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputLargeur = form.querySelector('[name="largeur"]');
  const inputTotalPieds = form.querySelector('[name="totalPieds"]');

  if (!inputQuantite || !inputLargeur || !inputTotalPieds) return;

  function calculerTotalPieds() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const largeur = parseFloat(inputLargeur.value) || 0;
    const total = (quantite * largeur) / 12;
    inputTotalPieds.value = Math.ceil(total);
    inputTotalPieds.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerTotalPieds);
  inputLargeur.addEventListener("input", calculerTotalPieds);
});


// (hauteur*2) + poignee + gousset = web 100
document.querySelectorAll('[id^="form-"]').forEach(form => {
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
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrix = form.querySelector('[name="prixFinauxMilleAvecProfit"]');
  const inputQuantiteInformative = form.querySelector('[name="quantiteInformative"]');
  const inputPrixInformatif = form.querySelector('[name="prixInformatif"]');

  if (!inputQuantite || !inputPrix || !inputQuantiteInformative || !inputPrixInformatif ) return;

  function calculerQuantiteInformative() {
    inputQuantiteInformative.value = inputQuantite.value;
  }

  function calculerPrixInformatif() {
    inputPrixInformatif.value = inputPrix.value;
  }

  inputQuantite.addEventListener("input", calculerQuantiteInformative);
  inputPrix.addEventListener("input", calculerPrixInformatif);
});

// Gestion du calcul des couts pour differentes quantites (variation prix)
document.querySelectorAll('[id^="form-"]').forEach(form => {
  if (!form.querySelector('[name="calculAutreQuantite"]')) return;
  form.querySelector('[name="calculAutreQuantite"]').addEventListener('click', () => {
      const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]'); // ton input principal
      const inputPrixFinal = form.querySelector('[name="prixInformatif"]'); // prix par mille calculé à la fin normalement ?
      const inputNouvelleQuantite = form.querySelector('[name="nvlQuantite"]');
      const inputNvPrix = form.querySelector('[name="nvPrix"]');

      if (!inputQuantite || !inputPrixFinal || !inputNouvelleQuantite || !inputNvPrix) {
          alert("Un ou plusieurs champs requis sont manquants.");
          return;
      }

      const ancienneQuantite = inputQuantite.value;
      const nouvelleQuantite = parseFloat(inputNouvelleQuantite.value);

      if (isNaN(nouvelleQuantite) || nouvelleQuantite <= 0) {
          alert("Veuillez entrer une nouvelle quantité valide.");
          return;
      }

      // Remplacement temporaire
      inputQuantite.value = nouvelleQuantite;
      inputQuantite.dispatchEvent(new Event("input", { bubbles: true }));

      // Laisser le temps aux autres scripts de recalculer les prix
      setTimeout(() => {
          const prixCalcule = parseFloat(inputPrixFinal.value);
          if (!isNaN(prixCalcule)) {
              inputNvPrix.value = prixCalcule.toFixed(2);
          } else {
              alert("Impossible de calculer le prix. Vérifiez vos scripts de calcul.");
          }

          // Restauration
          inputQuantite.value = ancienneQuantite;
          inputQuantite.dispatchEvent(new Event("input", { bubbles: true }));
      }, 200); // délai pour laisser les autres scripts réagir
  });
});

// nbBoites (quantite) * coutBoite (coutRouleau) + totalPalettes * coutPalette = coutTotauxProductionEmballage (bonus)
document.querySelectorAll('[id^="form-"]').forEach(form => {
  const inputNbBoites = form.querySelector('[name="nbBoites"]') || document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputCoutBoite = form.querySelector('[name="coutBoite"]') || form.querySelector('[name="coutRouleau"]');
  const inputTotalPalettes = form.querySelector('[name="totalPalettes"]');
  const inputCoutPalette = form.querySelector('[name="coutPalette"]');
  const inputResultat = form.querySelector('[name="coutTotauxProductionEmballage"]');

  if (!inputResultat) return;

  function calculerCoutEmballage() {
    const nb = parseFloat(inputNbBoites?.value) || 0;
    const coutUnitaire = parseFloat(inputCoutBoite?.value) || 0;
    const palettes = parseFloat(inputTotalPalettes?.value) || 0;
    const coutPalette = parseFloat(inputCoutPalette?.value) || 0;

    const total = (nb * coutUnitaire) + (palettes * coutPalette);
    inputResultat.value = total.toFixed(2);
    inputResultat.dispatchEvent(new Event("input", { bubbles: true }));
  }

  [inputNbBoites, inputCoutBoite, inputTotalPalettes, inputCoutPalette].forEach(input => {
    if (input) input.addEventListener("input", calculerCoutEmballage);
  });
});

// prixLivraison = coutTotauxProductionLivraison
document.querySelectorAll("form").forEach(form => {
    const inputPrixLivraison = form.querySelector('[name="prixLivraison"]');
    const inputCoutLivraison = form.querySelector('[name="coutTotauxProductionLivraison"]');

    if (inputPrixLivraison && inputCoutLivraison) {
      inputPrixLivraison.addEventListener("input", () => {
        const prix = parseFloat(inputPrixLivraison.value) || 0;
        inputCoutLivraison.value = prix.toFixed(2);
        inputCoutLivraison.dispatchEvent(new Event("input", { bubbles: true }));
      });
    }
  });

///////////////////////////// TAPE //////////////////////////////// 
const formTape = document.getElementById('form-tape');

//  largeur / largeurTape = nbPistes (FormTape)
if (formTape) {
  const selectLargeur = formTape.querySelector('#largeur');
  const selectLargeurTape = formTape.querySelector('#largeurTape');
  const inputNbPistes = formTape.querySelector('#nbPistes');

  function calculerNbPistes() {
    const largeur = parseFloat(selectLargeur.value) || 0;
    const largeurTape = parseFloat(selectLargeurTape.value) || 1;

    let nbPistes = 0;
    if (largeurTape !== 1) nbPistes = largeur / largeurTape;

    inputNbPistes.value = nbPistes > 0 ? Math.floor(nbPistes) : 0;
    inputNbPistes.dispatchEvent(new Event('input', { bubbles: true }));
  }

  selectLargeur.addEventListener('change', calculerNbPistes);
  selectLargeurTape.addEventListener('change', calculerNbPistes);
}

// (longueur / repetitionsTape) * quantite = totalRepetitionsTape (FormTape)
if (formTape) {
  const inputLongueur = formTape.querySelector('#longueurTape');
  const inputRepetition = formTape.querySelector('#repetitionsTape');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('#quantite');
  const inputTotal = formTape.querySelector('#totalRepetitionsTape');

  function calculerRepetitions() {
    const longueurMetres = parseFloat(inputLongueur.value) || 0;
    const repetitionPouces = parseFloat(inputRepetition.value) || 1;
    const quantite = parseFloat(inputQuantite.value) || 0;

    // 1 mètre = 39.3701 pouces
    const longueurEnPouces = longueurMetres * 39.3701;
    const total = (longueurEnPouces / repetitionPouces) * quantite;

    inputTotal.value = total > 0 ? Math.floor(total) : 0;
    inputTotal.dispatchEvent(new Event('input', { bubbles: true }));
  }

  inputLongueur.addEventListener('input', calculerRepetitions);
  inputRepetition.addEventListener('input', calculerRepetitions);
  inputQuantite.addEventListener('input', calculerRepetitions);
}

// quantite / (nbPistes * ((longueur / longueurTape).floor())) = totalMateriauTape (FormTape)
if (formTape) {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputLongueur = formTape.querySelector('[name="longueur"]');
  const inputLongueurTape = formTape.querySelector('[name="longueurTape"]');
  const inputNbPistes = formTape.querySelector('[name="nbPistes"]');
  const inputTotalMateriauTape = formTape.querySelector('[name="totalMateriauTape"]');

  function calculerTotalMateriauTape() {
      const quantite = parseFloat(inputQuantite.value) || 0;
      const longueur = parseFloat(inputLongueur.value) || 0;
      const longueurTape = parseFloat(inputLongueurTape.value) || 1;
      const nbPistes = parseFloat(inputNbPistes.value) || 1;

      const morceauxParRouleau = Math.floor(longueur / longueurTape);
      const denominateur = nbPistes * morceauxParRouleau;
      const total = denominateur > 0 ? Math.ceil(quantite / denominateur) : 0;

      inputTotalMateriauTape.value = total;
      inputTotalMateriauTape.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputQuantite.addEventListener("input", calculerTotalMateriauTape);
  inputLongueur.addEventListener("change", calculerTotalMateriauTape);
  inputLongueurTape.addEventListener("input", calculerTotalMateriauTape);
  inputNbPistes.addEventListener("input", calculerTotalMateriauTape);
}

// (surface(num) / (largeur * repetitionsTape)) * 100 = couverture(num) (FormTape)
if (formTape) {
  const inputLargeur = formTape.querySelector('[name="largeur"]');
  const inputRepetition = formTape.querySelector('[name="repetitionsTape"]');

  function calculerCouverturesTape() {
    const largeurMM = parseFloat(inputLargeur.value) || 0;
    const repetitionsPouces = parseFloat(inputRepetition.value) || 0;

    const largeurPouces = largeurMM / 25.4;
    const surfaceTotale = largeurPouces * repetitionsPouces;

    if (surfaceTotale === 0) return;

    for (let i = 1; i <= 2; i++) {
      const inputSurface = formTape.querySelector(`[name="surface${i}"]`);
      const inputCouverture = formTape.querySelector(`[name="couverture${i}"]`);

      if (inputSurface && inputCouverture) {
        const surface = parseFloat(inputSurface.value) || 0;
        const couverture = (surface / surfaceTotale) * 100;
        inputCouverture.value = couverture.toFixed(2);
        inputCouverture.dispatchEvent(new Event("input", { bubbles: true }));
      }
    }
  }

  // Écouteurs spécifiques au formulaire form-tape
  inputLargeur.addEventListener("input", calculerCouverturesTape);
  inputRepetition.addEventListener("input", calculerCouverturesTape);

  for (let i = 1; i <= 2; i++) {
    const input = formTape.querySelector(`[name="surface${i}"]`);
    if (input) input.addEventListener("input", calculerCouverturesTape);
  }
}

// (0.00000331767 * ((largeur * repetitionsTape) (pouces)) * totalRepetitionsTape) * (couverture(num) / 100) = kg(num)
if (formTape) {
  const inputLargeur = formTape.querySelector('[name="largeur"]');
  const inputRepetition = formTape.querySelector('[name="repetitionsTape"]');
  const inputTotalRepetitions = formTape.querySelector('[name="totalRepetitionsTape"]');

  function calculerKgParSurface() {
    const largeurMM = parseFloat(inputLargeur.value) || 0;
    const repetitionPouces = parseFloat(inputRepetition.value) || 0;
    const totalReps = parseFloat(inputTotalRepetitions.value) || 0;

    const largeurPouces = largeurMM / 25.4;
    const surfaceEnPouces = largeurPouces * repetitionPouces;

    for (let i = 1; i <= 2; i++) {
      const inputCouverture = formTape.querySelector(`[name="couverture${i}"]`);
      const inputKg = formTape.querySelector(`[name="kg${i}"]`);

      if (inputCouverture && inputKg) {
        const couverturePourcent = parseFloat(inputCouverture.value) || 0;
        const kg = 0.00000331767 * surfaceEnPouces * totalReps * (couverturePourcent / 100);
        inputKg.value = kg.toFixed(2);
        inputKg.dispatchEvent(new Event("input", { bubbles: true }));
      }
    }
  }

  // Listeners
  inputLargeur.addEventListener("input", calculerKgParSurface);
  inputRepetition.addEventListener("input", calculerKgParSurface);
  inputTotalRepetitions.addEventListener("input", calculerKgParSurface);

  for (let i = 1; i <= 2; i++) {
    const input = formTape.querySelector(`[name="couverture${i}"]`);
    if (input) input.addEventListener("input", calculerKgParSurface);
  }
}

// (longueurTape * quantite) / metresParHeureTape = dureeTotaleImpression
if (formTape) {
  const inputLongueurTape = formTape.querySelector('[name="longueurTape"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputMetresParHeure = formTape.querySelector('[name="metresParHeureTape"]');
  const inputDureeTotale = formTape.querySelector('[name="dureeTotaleImpression"]');

  function calculerDureeImpression() {
    const longueur = parseFloat(inputLongueurTape.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 0;
    const mph = parseFloat(inputMetresParHeure.value) || 0;

    if (longueur === 0 || quantite === 0 || mph === 0) {
      inputDureeTotale.value = '';
      return;
    }

    const duree = ((longueur * quantite) / mph);
    inputDureeTotale.value = duree.toFixed(2); // durée en heures
    inputDureeTotale.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputLongueurTape.addEventListener("input", calculerDureeImpression);
  inputQuantite.addEventListener("input", calculerDureeImpression);
  inputMetresParHeure.addEventListener("input", calculerDureeImpression);
}

// totalMateriauTape * 37 = prixMateriau
if (formTape) {
  const inputTotalMateriau = formTape.querySelector('[name="totalMateriauTape"]');
  const inputPrixMateriau = formTape.querySelector('[name="prixMateriau"]');

  function calculerPrixMateriau() {
    const total = parseFloat(inputTotalMateriau.value) || 0;
    const prix = total * 37;

    inputPrixMateriau.value = prix.toFixed(2);
    inputPrixMateriau.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputTotalMateriau.addEventListener("input", calculerPrixMateriau);
}

// prixMateriau = coutTotauxProductionMateriau
if (formTape) {
  const inputPrixMateriau = formTape.querySelector('[name="prixMateriau"]');
  const inputCoutMateriau = formTape.querySelector('[name="coutTotauxProductionMateriau"]');

  function copierPrixVersCoutMateriau() {
    if (!inputPrixMateriau || !inputCoutMateriau) return;

    inputCoutMateriau.value = inputPrixMateriau.value;
    inputCoutMateriau.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputPrixMateriau?.addEventListener("input", copierPrixVersCoutMateriau);
}

////////////////////////// FIN TAPE ///////////////////////////////////

///////////////////////// SACS PAPIER /////////////////////////////////

const formSacsPapier = document.getElementById('form-sacsPapier');

// (formatSacPapier.split(x, 0) * formatSacPapier.split(x, 2)) * quantite = poucesCarresParQuantiteAProduire 
if (formSacsPapier) {
  const inputFormat = formSacsPapier.querySelector("#formatSacPapier");
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputResultat = formSacsPapier.querySelector("#poucesCarresParQuantiteAProduire");

  function calculerSurface() {
    const format = inputFormat.value.trim();
    const quantite = parseFloat(inputQuantite.value) || 0;

    // Extraction des dimensions
    const dimensions = format.split("x");

    if (dimensions.length < 3) {
      inputResultat.value = "";
      return;
    }

    const largeur = parseFloat(dimensions[0]);
    const hauteur = parseFloat(dimensions[2]);
    const surface = largeur * hauteur * quantite;

    inputResultat.value = surface.toFixed(2);
    inputResultat.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputFormat.addEventListener("change", calculerSurface);
  inputQuantite.addEventListener("input", calculerSurface);
}

// (surface(num) / (poucesCarresParQuantiteAProduire / quantite)) * 100 = couverture(num)
if (formSacsPapier) {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputSurfaceTotal = formSacsPapier.querySelector("#poucesCarresParQuantiteAProduire");

  function calculerCouvertures() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const surfaceParQuantite = parseFloat(inputSurfaceTotal.value) || 0;

    if (quantite === 0 || surfaceParQuantite === 0) return;

    const surfaceUnitaire = surfaceParQuantite / quantite;

    for (let i = 1; i <= 5; i++) {
      const inputSurface = formSacsPapier.querySelector(`[name="surface${i}"]`);
      const inputCouverture = formSacsPapier.querySelector(`[name="couverture${i}"]`);

      if (inputSurface && inputCouverture) {
        const surface = parseFloat(inputSurface.value) || 0;
        const couverture = (surface / surfaceUnitaire) * 100;

        inputCouverture.value = couverture.toFixed(2);
        inputCouverture.dispatchEvent(new Event("input", { bubbles: true }));
      }
    }
  }

  inputQuantite.addEventListener("input", calculerCouvertures);
  inputSurfaceTotal.addEventListener("input", calculerCouvertures);

  for (let i = 1; i <= 5; i++) {
    const inputSurface = formSacsPapier.querySelector(`[name="surface${i}"]`);
    if (inputSurface) inputSurface.addEventListener("input", calculerCouvertures);
  }
}

// quantite / sacsParHeurePapier =  dureeTotaleImpression

if (formSacsPapier) {
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputSacsParHeure = formSacsPapier.querySelector('[name="sacsParHeurePapier"]');
  const inputDuree = formSacsPapier.querySelector('[name="dureeTotaleImpression"]');

  function calculerDureeImpression() {
    const quantite = parseFloat(inputQuantite.value) || 0;
    const sacsParHeure = parseFloat(inputSacsParHeure.value) || 0;
    if (sacsParHeure > 0) {
      const duree = quantite / sacsParHeure;
      inputDuree.value = duree.toFixed(2);
      inputDuree.dispatchEvent(new Event("input", { bubbles: true }));
    }
  }

  inputQuantite.addEventListener("input", calculerDureeImpression);
  inputSacsParHeure.addEventListener("input", calculerDureeImpression);
}

// prixUnitaireSacsPapier * quantite = prixSacsPapier
if (formSacsPapier) {
  const inputPrixUnitaire = formSacsPapier.querySelector('[name="prixUnitaireSacsPapier"]');
  const inputQuantite = document.getElementById("form-inputsCommunItem").querySelector('[name="quantite"]');
  const inputPrixTotal = formSacsPapier.querySelector('[name="prixSacsPapier"]');

  function calculerPrixTotal() {
    const prixUnitaire = parseFloat(inputPrixUnitaire.value) || 0;
    const quantite = parseFloat(inputQuantite.value) || 0;
    const total = prixUnitaire * quantite;

    inputPrixTotal.value = total.toFixed(2);
    inputPrixTotal.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputPrixUnitaire.addEventListener("input", calculerPrixTotal);
  inputQuantite.addEventListener("input", calculerPrixTotal);
}

// prixSacsPapier = coutTotauxProductionMateriau
if (formSacsPapier) {
  const inputPrixSacs = formSacsPapier.querySelector('[name="prixSacsPapier"]');
  const inputCoutMateriau = formSacsPapier.querySelector('[name="coutTotauxProductionMateriau"]');

  function copierPrixDansCout() {
    const prix = parseFloat(inputPrixSacs.value) || 0;
    inputCoutMateriau.value = prix.toFixed(2);
    inputCoutMateriau.dispatchEvent(new Event("input", { bubbles: true }));
  }

  inputPrixSacs.addEventListener("input", copierPrixDansCout);
}

/////////////////////// FIN SACS PAPIER ///////////////////////////////
