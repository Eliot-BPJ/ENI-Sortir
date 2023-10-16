// Attendre que le document HTML soit complètement chargé avant d'exécuter ce code

$(document).ready(function () {
    // Gestion du bouton "Modifier"
    $('.btn-edit').click(function () {
        let tr = $(this).closest('tr');
        let id = $(this).data('site-id');
        let nomCell = tr.find('td:first-child');

        let nomValue = nomCell.text();
        let inputField = '<input type="text" class="form-control" name="editedSite" value="' + nomValue + '">';
        nomCell.html(inputField);

        // Sauvegarder l'ID dans une variable
        tr.data('editing-site-id', id);

        // Masquer le bouton "Modifier" et afficher le bouton "Sauvegarder"
        tr.find('.btn-edit').hide();
        tr.find('.btn-save').show();
    });

    // Gestion du bouton "Sauvegarder"
    $('.btn-save').click(function () {
        let tr = $(this).closest('tr');
        let nomCell = tr.find('td:first-child');
        let updatedNom = nomCell.find('input').val();

        // Récupérer l'ID à partir de la variable
        let id = tr.data('editing-site-id');
        // Mettre à jour le nom du site via une requête AJAX
        $.ajax({
            type: 'POST',
            url: '/site/modifier/' + id,
            data: { editedSite: updatedNom },
            success: function (data) {
                // Mettre à jour la cellule du nom avec la nouvelle valeur
                nomCell.html(updatedNom);

                // Masquer le bouton "Sauvegarder" et afficher le bouton "Modifier"
                tr.find('.btn-edit').show();
                tr.find('.btn-save').hide();

                // Afficher un message de succès ou gérer les erreurs ici
            },
            error: function (data) {
                // Gérer les erreurs ici
            }
        });
    });
});
