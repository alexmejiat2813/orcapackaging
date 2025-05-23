//////////////////////////////////////////////////////
// GESTION DES FORMULAIRES POUR LARAVEL
//////////////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Gestion bouton 'soumettreItem'
    const boutonItem = document.getElementById('soumettreItem');
    if (boutonItem) {
        boutonItem.addEventListener('click', async () => {
            const commande = document.getElementById('commande')?.value;
            const formId = 'form-' + commande;

            const formInputsCommun = document.getElementById('form-inputsCommunItem');
            const formCommande = document.getElementById(formId);
            const formData = new FormData();

            ajouterChampsAuFormData(formData, formInputsCommun);
            ajouterChampsAuFormData(formData, formCommande);

            if (validerForm(formData)) {
                console.log(formData);
                try {
                    const response = await fetch('/sales/estimates_item/storeItem', {
                        method: 'POST',
                        //headers: {
                        //    'X-CSRF-TOKEN': csrfToken,
                        //    'Accept': 'application/json'
                        //},
                        body: formData,
                        credentials: 'same-origin'
                    });
                
                    // Si pas de réponse (coupure réseau ou crash total)
                    if (!response) {
                        throw new Error('Aucune réponse reçue du serveur');
                    }
                
                    const contentType = response.headers.get('content-type');
                    console.log(response);
                
                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                    
                        if (!response.ok) {
                            alert('Erreur Laravel (contacter un administrateur):', data);
                        } else {
                            alert('Item enregistré/modifié avec succès');
                            window.location.reload();
                        }
                    } else {
                        const text = await response.text();
                        console.warn('Réponse inattendue (non-JSON) :', text);
                    }
                } catch (error) {
                    alert('Erreur AJAX (contacter un administrateur) :', error.message);
                }
            }
        });
    }

    // Gestion bouton 'soumettreSoumission'
    const boutonSoumission = document.getElementById('soumettreSoumission');
    if (boutonSoumission) {
        boutonSoumission.addEventListener('click', async () => {
            const formSoumission = document.getElementById('form-ParamsBase');
            const formData = new FormData();

            ajouterChampsAuFormData(formData, formSoumission);

            if (validerForm(formData)) {
                try {
                    const response = await fetch('/sales/estimates/storeSoumission', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData,
                        credentials: 'same-origin'
                    });
                
                    // Si pas de réponse (coupure réseau ou crash total)
                    if (!response) {
                        throw new Error('Aucune réponse reçue du serveur');
                    }
                
                    const contentType = response.headers.get('content-type');
                
                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                    
                        if (!response.ok) {
                            alert('Erreur Laravel (contacter un administrateur):', data);
                        } else {
                            alert('Soumission enregistrée avec succès');
                            window.location.reload();
                        }
                    } else {
                        const text = await response.text();
                        console.warn('Réponse inattendue (non-JSON) :', text);
                    }
                } catch (error) {
                    alert('Erreur AJAX (contacter un administrateur) :', error.message);
                }
            }
        });
    }
});

// Fonction de validation d'un formulaire FormData
function validerForm(formData) {
    let estValide = true;
    let messageErreurs = [];

    const champsFormData = Array.from(formData.keys());

    champsFormData.forEach(nomChamp => {
        const element = document.querySelector(`[name="${nomChamp}"]`);
        if (element && element.required) {
            const valeur = formData.get(nomChamp);

            if (!valeur) {
                estValide = false;
                const label = element.labels?.[0]?.textContent || element.name;
                messageErreurs.push(`Le champ "${label}" est requis`);
            } else {
                if (element.type === 'email' && !validerEmail(valeur)) {
                    estValide = false;
                    const label = element.labels?.[0]?.textContent || element.name;
                    messageErreurs.push(`L'email "${label}" n'est pas valide`);
                }
                if (element.type === 'tel' && !validerTelephone(valeur)) {
                    estValide = false;
                    const label = element.labels?.[0]?.textContent || element.name;
                    messageErreurs.push(`Le téléphone "${label}" doit contenir exactement 10 chiffres`);
                }
            }
        }
    });

    if (!estValide) {
        alert(`Veuillez corriger les erreurs suivantes :\n\n${messageErreurs.join('\n')}`);
    }

    return estValide;
}

// Fonction d’ajout des champs à FormData
function ajouterChampsAuFormData(formData, form) {
    if (!form) return;
    Array.from(form.elements).forEach(element => {
        if (element.name && element.value !== undefined) {
            formData.append(element.name, element.value);
        }
    });
}

// Fonctions de validation spécifiques
function validerEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validerTelephone(tel) {
    return /^[0-9]{10}$/.test(tel);
}