// Attendre que le document HTML soit complètement chargé avant d'exécuter ce code

$(document).ready(function () {
    // Lorsque l'on clique sur un élément avec la classe 'btn-edit'
    $('.btn-edit').on('click', function () {
        // Récupérer l'identifiant du site à partir de l'attribut 'data-site-id' de l'élément cliqué
        let siteId = $(this).data('site-id');

        // Trouver la première cellule (colonne) du même ligne (tr) que le bouton cliqué
        let siteCell = $(this).closest('tr').find('td:first');

        // Récupérer le nom du site à partir du texte de la cellule
        let siteName = siteCell.text();

        let inputField = $('<input type="text" id="edit-site-' + siteId + '" value="' + siteName + '">');
        let saveButton = $('<button class="btn-save" data-site-id="' + siteId + '">Sauvegarder</button>');

        // Remplacer le texte de la cellule par un champ de texte <input> contenant le nom du site
        siteCell.empty().append(inputField, saveButton);

    });

    // Lorsque l'on clique sur un élément avec la classe 'btn-save' (utilisation de delegation d'événements)
    $(document).on('click', '.btn-save', function () {
        // Récupérer l'identifiant du site à partir de l'attribut 'data-site-id' de l'élément cliqué
        let siteId = $(this).data('site-id');

        // Récupérer la valeur modifiée du champ de texte correspondant à cet identifiant
        let editedSite = $('#edit-site-' + siteId).val();

        // Conserver une référence à l'élément input et au bouton "Sauvegarder"
        let inputField = $('#edit-site-' + siteId);
        let saveButton = $(this);

        // Effectuer une requête AJAX pour mettre à jour le site dans la base de données
        $.ajax({
            type: 'POST', // Méthode HTTP (POST pour les mises à jour)
            url: '/site/modifier/' + siteId, // URL de votre API ou script côté serveur
            data: {
                editedSite: editedSite // Envoyer le nouveau nom du site
            },
            success: function (response) {
                // La mise à jour côté serveur a réussi

                // Remplacer l'input par un champ texte avec le nouveau nom du site
                inputField.replaceWith('<span>' + editedSite + '</span>');
                // Masquer le bouton "Sauvegarder"
                saveButton.hide();
            },
            error: function (xhr, status, error) {
                // La mise à jour côté serveur a échoué
                console.log("Erreur lors de la mise à jour : " + error);
            }
        });
    });
});
