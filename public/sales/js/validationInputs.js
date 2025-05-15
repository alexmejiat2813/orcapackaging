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
