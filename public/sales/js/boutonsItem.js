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
        .then(async res => {
            const json = await res.json();
            
            if (!res.ok || json.success === false) {
                // Affiche une alerte avec le message d'erreur s'il est disponible
                alert(json.message || 'Échec de la suppression. Veuillez réessayer.');
                return;
            }
        
            // Appel du callback seulement si tout est OK
            callback(json);
        })
        .catch(error => {
            console.error('Erreur réseau :', error);
            alert("Erreur réseau ou serveur. Veuillez vérifier votre connexion.");
        });
    }

    // Fonction permettant de recuperer les donnees des ItemsSynologyodificationLog
    function afficherHistoriqueModifications(itemId) {
        const sectionModif = document.getElementById("formulaireModificationSection");
        sectionModif.classList.remove("d-none")
        // Appel au contrôleur Laravel avec fetch()
        fetch(`/sales/estimates_item/modification?item_id=${itemId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Erreur de récupération des modifications');
            return response.json();
        })
        .then(response => {
            if (!response.success) throw new Error('Réponse serveur invalide');
        
            const source = {
                datatype: "json",
                localdata: response.data,
                datafields: [
                    { name: 'utilisateur', type: 'string' },
                    { name: 'date', type: 'date' },
                    { name: 'commentaire', type: 'string' }
                ]
            };
        
            const dataAdapter = new $.jqx.dataAdapter(source);
        
            if (!$("#modificationGrid").hasClass("jqx-widget")) {
                $("#modificationGrid").jqxGrid({
                    width: '100%',
                    height: 300,
                    source: dataAdapter,
                    pageable: true,
                    columnsresize: true,
                    columns: [
                        { text: 'Utilisateur', datafield: 'utilisateur', width: 150 },
                        { text: 'Date', datafield: 'date', cellsformat: 'yyyy-MM-dd HH:mm' },
                        { text: 'Commentaire', datafield: 'commentaire' }
                    ]
                });
            } else {
                $("#modificationGrid").jqxGrid('source', dataAdapter);
            }
        })
        .catch(error => {
            console.error("Erreur lors du chargement des modifications :", error);
            alert("Impossible de charger l'historique des modifications.");
        });
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
            body: JSON.stringify(data),
            credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = data.data;

                    afficherHistoriqueModifications(item.ID);

                    // 1️⃣ Appliquer d'abord la valeur du SELECT "commande"
                    if ('commande' in item) {
                        const selectCommande = document.querySelector('[name="commande"]');
                        if (selectCommande) {
                            selectCommande.value = item.commande;
                            selectCommande.dispatchEvent(new Event('change'));
                        }
                    }

                    setTimeout(() => {
                        let activeForm = null;
                        if (item.commande !== '- Aucun -') {
                            activeForm = document.querySelector(`#form-${item.commande}`);
                        }
                        const communForm = document.querySelector('#form-inputsCommunItem');

                        Object.entries(item).forEach(([key, value]) => {
                            // Cas special pour le select
                            if (key === 'commande') return;
                        
                            // Essayer dans le formulaire spécifique d’abord
                            let input = activeForm ? activeForm.querySelector(`[name="${key}"]`) : null;

                            // Sinon dans le formulaire commun
                            if (!input && communForm) {
                                input = communForm.querySelector(`[name="${key}"]`);
                            }
                        
                            if (input) {
                                if (input.tagName === 'SELECT') {
                                    input.value = value;
                                    input.dispatchEvent(new Event('change'));
                                } else if (key === 'formatProduit') {
                                    const selectedValues = value.split(',').map(v => v.trim());
                                    document.querySelectorAll('.formatProduit-checkbox').forEach(checkbox => {
                                        const shouldBeChecked = selectedValues.includes(checkbox.value);
                                        
                                        if (checkbox.checked !== shouldBeChecked) {
                                            checkbox.checked = shouldBeChecked;
                                        
                                            // 🔥 Simuler un vrai changement (comme si l’utilisateur avait cliqué)
                                            const event = new Event('change', { bubbles: true });
                                            checkbox.dispatchEvent(event);
                                        }
                                    });
                                } else {
                                    input.value = value;
                                    input.dispatchEvent(new Event("input", { bubbles: true }));
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
                $('#jqxgrid').jqxGrid('updatebounddata');
            });
        }
    });

    // 🧬 Bouton "Copier"
    $copier.on("click", function () {
        const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
        const data = grid.jqxGrid('getrowdata', selectedRowIndex);

        envoyerDonneesVersLaravel('/sales/estimates_item/copier', data, function () {
            $('#jqxgrid').jqxGrid('updatebounddata');
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
