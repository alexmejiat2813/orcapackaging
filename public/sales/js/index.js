// Fonction pour ajouter des jours à une date donnée
function addDays(date, days) {
    const result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

// Fonction pour formater une date en string YYYY-MM-DD
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

// Une fois le DOM chargé, on affecte la date au champ
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('inputDateLivraison');
    if (dateInput) {
        const futureDate = addDays(new Date(), 30);
        dateInput.value = formatDate(futureDate);
    }

    // Réinitialise le select à -1 à chaque chargement
    const clientSelect = document.getElementById('clients');
    if (clientSelect) {
        clientSelect.value = '-1';
        // Si tu utilises Select2, pense à le rafraîchir
        if ($(clientSelect).hasClass("select2-hidden-accessible")) {
            $(clientSelect).trigger('change');
        }
    }

    // Et tu peux cacher les assets directement ici aussi
    document.getElementById('container-assets').style.display = 'none';
});

// Validation de l'email
const emailInput = document.getElementById('inputEmailClient');
const validationIcon = document.getElementById('validationIconEmail');

emailInput.addEventListener('input', function() {
    const email = this.value.trim();
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

    // Affichage de l'icône ✅ ou ❌
    validationIcon.textContent = email ? (isValid ? '✅' : '❌') : '';
    validationIcon.className = email ? (isValid ? 'valid' : 'invalid') : '';
});

// Validation du téléphone
const inputTel = document.getElementById('inputTelephoneClient');
const validationIconTel = document.getElementById('validationIconTel');

inputTel.addEventListener('input', function() {
    const tel = this.value.trim();
    const isValid = /^[0-9]{10}$/.test(tel);

    // Affichage de l'icône ✅ ou ❌
    validationIconTel.textContent = tel ? (isValid ? '✅' : '❌') : '';
    validationIconTel.className = tel ? (isValid ? 'valid' : 'invalid') : '';
}); 

