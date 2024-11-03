

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
document.addEventListener('submit', async function (e){
    e.preventDefault();
    let formData = new FormData(e.target);
    let isValid = true;
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    formData.forEach(function(value, key) {
        if (!value.trim()) {
            isValid = false;
            return;
        }
    });
    if (!isValid) {
        document.getElementById('loginNotiftext').innerHTML= 'Please fill in all fields';
        openModalForm('loginWarning');
        return;
    }
    if (!emailRegex.test(formData.get('log_email'))){
        document.getElementById('loginNotiftext').innerHTML= 'Please input the right Email format';
        openModalForm('loginWarning');
        return;
    }
    if (formData.get('log_password').length < 8){
        document.getElementById('loginNotiftext').innerHTML= 'Password must be at least 8 characters';
        openModalForm('loginWarning');

        return;
    }

    $.ajax({
        url: '../ajax.php?action=login',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            document.getElementById('loader').classList.remove('hidden');
            if (response.response === 1) {
                setTimeout(function() {

                    document.getElementById('loader').classList.add('hidden');
                }, 2500);
                window.location.href = response.redirect;
            } else{
                document.getElementById('loginNotiftext').innerHTML= 'Incorrect email or password';
                openModalForm('loginWarning');
                document.getElementById('loader').classList.add('hidden');
            }
            console.log(response.message);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

});