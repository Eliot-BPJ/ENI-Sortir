// assets/js/ville.js
$(document).ready(function() {
    $('.btn-edit').click(function() {
        let tr = $(this).closest('tr');
        let nomTd = tr.find('.ville-nom');
        let codePostalTd = tr.find('.ville-codepostal');
        let id = $(this).data('ville-id');

        // Récupérer les valeurs actuelles
        let nom = nomTd.text();
        let codePostal = codePostalTd.text();

        // Remplacer les valeurs par des inputs
        nomTd.html('<input type="text" class="form-control" value="' + nom + '">');
        codePostalTd.html('<input type="text" class="form-control" value="' + codePostal + '">');

        // Sauvegarder l'ID dans une variable
        tr.data('editing-ville-id', id);

        // Cacher le bouton "Modifier" et afficher le bouton "Sauvegarder"
        tr.find('.btn-edit').hide();
        tr.find('.btn-save').show();
    });

    $('.btn-save').click(function() {
        let tr = $(this).closest('tr');
        let nomInput = tr.find('.ville-nom input');
        let codePostalInput = tr.find('.ville-codepostal input');

        // Récupérer l'ID à partir de la variable
        let id = tr.data('editing-ville-id');

        let editedNom = nomInput.val();
        let editedCodePostal = codePostalInput.val();

        // Envoyer les données au serveur pour mise à jour
        $.ajax({
            method: 'POST',
            url: '/ville/modifier/' + id,
            data: {
                editedNom: editedNom,
                editedCodePostal: editedCodePostal
            },
            success: function(response) {
                // Mettre à jour l'affichage avec les nouvelles valeurs
                nomInput.replaceWith(editedNom);
                codePostalInput.replaceWith(editedCodePostal);

                // Cacher le bouton "Sauvegarder" et afficher le bouton "Modifier"
                tr.find('.btn-save').hide();
                tr.find('.btn-edit').show();
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la mise à jour de la ville.');
            }
        });
    });
});
