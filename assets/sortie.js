function appearLieu() {
    let div = document.getElementById('form-lieu');
    let input = document.getElementById('input-ville');
    input.style.display = 'none';
    div.style.display = 'block';
    let inputs = div.querySelectorAll("input");
    let selectVille = div.querySelectorAll("select");
    let selects = input.querySelectorAll("select");

    inputs.forEach(function(inputs) {
        inputs.setAttribute("required", "required");
    });
    selectVille.forEach(function(selectVille) {
        console.log(selectVille)
        selectVille.setAttribute("required", "required");
    });
    selects.forEach(function(selects) {
        selects.removeAttribute("required");
    });
}
function disappearLieu() {
    let div = document.getElementById('form-lieu');
    let input = document.getElementById('input-ville');
    input.style.display = 'block';
    div.style.display = 'none';
    let inputs = div.querySelectorAll("input");
    let selectVille = div.querySelectorAll("select");
    let selects = input.querySelectorAll("select");

    selects.forEach(function(selects) {
        selects.setAttribute("required", "required");
    });
    inputs.forEach(function(inputs) {
        inputs.removeAttribute("required");
        inputs.value = ""
    });
    selectVille.forEach(function(selectVille) {
        selectVille.removeAttribute("required");
    });
}