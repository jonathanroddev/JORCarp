(function () {
    'use strict';
    window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

function confirmPassword() {
    let password = document.getElementById('userpass');
    let confirm = document.getElementById('confirmpass');
    if (password.value != confirm.value) confirm.setCustomValidity('invalid');
    else confirm.setCustomValidity('');
    console.log(confirm.checkValidity());
}