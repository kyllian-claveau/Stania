document.getElementById('showPassword').addEventListener('click', function() {
    var passwordFields = document.querySelectorAll('[id^="register_form_plainPassword"]');
    passwordFields.forEach(function(field) {
        if (field.type === 'password') {
            field.type = 'text';
        } else {
            field.type = 'password';
        }
    });
});