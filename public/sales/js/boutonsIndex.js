$(document).ready(function () {
    const grid = $("#jqxgrid");

    const $gerer = $("#gererBouton");
    const $supprimer = $("#supprimerBouton");
    const $copier = $("#copierBouton");
    const $modifier = $("#modifierBouton");

    $gerer.prop("disabled", true);
    $supprimer.prop("disabled", true);
    $copier.prop("disabled", true);
    $modifier.prop("disabled", true);

    grid.on("rowselect", function () {
        $gerer.prop("disabled", false);
        $supprimer.prop("disabled", false);
        $copier.prop("disabled", false);
        $modifier.prop("disabled", false);
    });

    grid.on("rowunselect", function () {
        if (grid.jqxGrid('getselectedrowindex') === -1) {
            $gerer.prop("disabled", true);
            $supprimer.prop("disabled", true);
            $copier.prop("disabled", true);
            $modifier.prop("disabled", true);
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

    $gerer.on("click", function () {
        const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
        const data = grid.jqxGrid('getrowdata', selectedRowIndex);

        envoyerDonneesVersLaravel('/sales/estimates/gerer', data, function () {
            window.location.href = '/sales/estimates_item';
        });
    });

    $supprimer.on("click", function () {
        if (confirm("Le fait de supprimer cette soumission la rend irrecuperable apres. Etes-vous sur de vouloir la supprimer ?")) {
            const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
            const data = grid.jqxGrid('getrowdata', selectedRowIndex);
            
            envoyerDonneesVersLaravel('/sales/estimates/supprimer', data, function () {
                window.location.reload();
            });
        }
    });

    $copier.on("click", function () {
        const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
        const data = grid.jqxGrid('getrowdata', selectedRowIndex);

        envoyerDonneesVersLaravel('/sales/estimates/copier', data, function () {
            window.location.reload();
        });
    });

    $modifier.on("click", function () {
        if (confirm("Vous perdrez la complétion de la soumission actuelle. Êtes-vous sûr ?")) {
            const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
            const rowData = grid.jqxGrid('getrowdata', selectedRowIndex);

            const fieldMap = {
                Client: 'clients',
                Nom: 'nomClient',
                Prenom: 'prenomClient',
                Email: 'emailClient',
                Telephone: 'telephoneClient',
                Nom_Travail: 'nomTravail',
                Date_Livraison: 'dateLivraisonSouhaitee'
            };


            fetch('/sales/estimates/modifier', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(rowData)
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || "Erreur lors du chargement");
                    return;
                }
            
                const soumission = data.data;
            
                Object.entries(soumission).forEach(([key, value]) => {
                    const mappedKey = fieldMap[key] || key;
                    const input = document.querySelector(`[name="${mappedKey}"]`);
                    if (input) {
                        const type = input.getAttribute('type');
                        if (input.tagName === 'SELECT') {
                            input.value = value;
                            input.dispatchEvent(new Event('change'));
                        } else if (type === 'checkbox' || type === 'radio') {
                            input.checked = Boolean(value);
                        } else {
                            input.value = value;
                        }
                    }
                });
            
                updateSoumissionStatus();
            })
            .catch(error => {
                console.error("Erreur réseau :", error);
                alert("Erreur lors de la requête.");
            });
        }
    });
});

// Pour affichage titre
async function updateSoumissionStatus() {
    try {
        const response = await fetch('/sales/estimates/getSession');
        const data = await response.json();

        const h1 = document.getElementById('titre-soumission');

        if (data.has) {
            h1.textContent = `Modifier Soumission n° ${data.id}`;
        } else {
            h1.textContent = 'Nouvelle Soumission';
        }

    } catch (error) {
        console.error("Erreur lors de la récupération de l'état de session :", error);
    }
}

updateSoumissionStatus();