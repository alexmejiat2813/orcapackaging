$(document).ready(function () {
    const grid = $("#jqxgrid");

    const $modifier = $("#modifierItemBouton");
    const $supprimer = $("#supprimerItemBouton");
    const $copier = $("#copierItemBouton");

    $modifier.prop("disabled", true);
    $supprimer.prop("disabled", true);
    $copier.prop("disabled", true);

    grid.on("rowselect", function () {
        $modifier.prop("disabled", false);
        $supprimer.prop("disabled", false);
        $copier.prop("disabled", false);
    });

    grid.on("rowunselect", function () {
        if (grid.jqxGrid('getselectedrowindex') === -1) {
            $modifier.prop("disabled", true);
            $supprimer.prop("disabled", true);
            $copier.prop("disabled", true);
        }
    });

    // 🧠 Utilitaire pour envoyer les données au backend Laravel
    function envoyerDonneesVersLaravel(url, data, callback) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(callback)
        .catch(error => console.error('Erreur réseau :', error));
    }

    // ⚙️ Bouton "Gérer"
    $modifier.on("click", function () {
        if (confirm("Vous perdrez la completion de l'item actuel. Etes-vous sur de vouloir modifier cet item ?")) {
            const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
            const data = grid.jqxGrid('getrowdata', selectedRowIndex);

            //envoyerDonneesVersLaravel('/sales/estimates_item/modifier', data, function () {});
            fetch('/sales/estimates_item/modifier', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = data.data;

                    // 1️⃣ Appliquer d'abord la valeur du SELECT "commande"
                    if ('commande' in item) {
                        const selectCommande = document.querySelector('[name="commande"]');
                        if (selectCommande) {
                            selectCommande.value = item.commande;
                            selectCommande.dispatchEvent(new Event('change'));
                        }
                    }

                    setTimeout(() => {
                        const activeForm = document.querySelector(`#form-${item.commande}`);
                        const communForm = document.querySelector('#form-inputsCommunItem');

                        Object.entries(item).forEach(([key, value]) => {
                            if (key === 'commande') return;
                        
                            // Essayer dans le formulaire spécifique d’abord
                            let input = activeForm ? activeForm.querySelector(`[name="${key}"]`) : null;
                        
                            // Sinon dans le formulaire commun
                            if (!input && communForm) {
                                input = communForm.querySelector(`[name="${key}"]`);
                            }
                        
                            if (input) {
                                const type = input.getAttribute('type');
                            
                                if (input.tagName === 'SELECT') {
                                    input.value = value;
                                    input.dispatchEvent(new Event('change'));
                                } else if (key === 'formatProduit') {
                                    const selectedValues = value.split(',').map(v => v.trim());
                                    document.querySelectorAll('.formatProduit-checkbox').forEach(checkbox => {
                                        checkbox.checked = selectedValues.includes(checkbox.value);
                                    });
                                    return;
                                } else {
                                    input.value = value;
                                }
                            }
                        });
                    }, 100); // 💡 petit délai pour que le bon formulaire soit visible si ça s’affiche dynamiquement
                    updateItemStatus();
                } else {
                    alert(data.message || 'Erreur lors du chargement.');
                }
            })
            .catch(error => {
                console.error('Erreur Fetch:', error);
                alert('Erreur réseau ou serveur.');
            });
        }
    });

    // 🗑️ Bouton "Supprimer"
    $supprimer.on("click", function () {
        if (confirm("Le fait de supprimer cet item le rend irrecuperable apres. Etes-vous sur de vouloir le supprimer ?")) {
            const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
            const data = grid.jqxGrid('getrowdata', selectedRowIndex);
            
            envoyerDonneesVersLaravel('/sales/estimates_item/supprimer', data, function () {
                window.location.reload();
            });
        }
    });

    // 🧬 Bouton "Copier"
    $copier.on("click", function () {
        const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
        const data = grid.jqxGrid('getrowdata', selectedRowIndex);

        envoyerDonneesVersLaravel('/sales/estimates_item/copier', data, function () {
            window.location.reload();
        });
    });
});

// Pour affichage titre
async function updateItemStatus() {
    try {
        const response = await fetch('/sales/estimates_item/getSession');
        const data = await response.json();

        const h1 = document.getElementById('titre-item');

        if (data.has) {
            h1.textContent = `Modifier Item n° ${data.id}`;
        } else {
            h1.textContent = 'Nouvel Item';
        }

    } catch (error) {
        console.error("Erreur lors de la récupération de l'état de session :", error);
    }
}

updateItemStatus();
