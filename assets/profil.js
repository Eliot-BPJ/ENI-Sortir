import './styles/profil.scss';

// Check if passwords match
$("#{{ form.currentPassword.vars.id }}, #{{ form.password.first.vars.id }}, #{{ form.password.second.vars.id }}").on("keyup", function () {
    if (
        $("#{{ form.currentPassword.vars.id }}").val() !== "" &&
        $("#{{ form.password.first.vars.id }}").val() !== "" &&
        $("#{{ form.password.first.vars.id }}").val() !== $("#{{ form.password.second.vars.id }}").val()
    ) {
        $("#{{ form.password.second.vars.id }}")[0].setCustomValidity(
            $("#{{ form.password.first.vars.id }}").val() === $("#{{ form.password.second.vars.id }}").val() ? '' : 'Les mots de passe ne correspondent pas.'
        );

    }
});

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    "use strict";
    window.addEventListener(
        "load",
        function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName("needs-validation");
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener(
                    "submit",
                    function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add("was-validated");
                    },
                    false
                );
            });
        },
        false
    );
})();