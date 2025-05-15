$(document).ready(function () {
    const grid = $("#jqxgrid");

    const $gerer = $("#gererBouton");
    const $supprimer = $("#supprimerBouton");
    const $copier = $("#copierBouton");

    $gerer.prop("disabled", true);
    $supprimer.prop("disabled", true);
    $copier.prop("disabled", true);

    grid.on("rowselect", function () {
        $gerer.prop("disabled", false);
        $supprimer.prop("disabled", false);
        $copier.prop("disabled", false);
    });

    grid.on("rowunselect", function () {
        if (grid.jqxGrid('getselectedrowindex') === -1) {
            $gerer.prop("disabled", true);
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
    $gerer.on("click", function () {
        const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
        const data = grid.jqxGrid('getrowdata', selectedRowIndex);

        envoyerDonneesVersLaravel('/sales/soumission/gerer', data, function () {
            window.location.href = '/sales/soumission/item';
        });
    });

    // 🗑️ Bouton "Supprimer"
    $supprimer.on("click", function () {
        const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
        const data = grid.jqxGrid('getrowdata', selectedRowIndex);

        envoyerDonneesVersLaravel('/sales/soumission/supprimer', data, function () {
            window.location.reload();
        });
    });

    // 🧬 Bouton "Copier"
    $copier.on("click", function () {
        const selectedRowIndex = grid.jqxGrid('getselectedrowindex');
        const data = grid.jqxGrid('getrowdata', selectedRowIndex);

        envoyerDonneesVersLaravel('/sales/soumission/copier', data, function () {
            window.location.reload();
        });
    });
});
