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
});
