document.getElementById('login_img').addEventListener('click', function() {
    window.location.href = 'index.php';
});
document.addEventListener('submit', function (e){
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
            if (parseInt(response) === 1) {
                setTimeout(function() {

                    document.getElementById('loader').classList.add('hidden');
                }, 2500);

                window.location.href = 'dashboard.php';
            } else if (parseInt(response) === 2) {
                document.getElementById('loginNotiftext').innerHTML= 'Incorrect email or password';
                openModalForm('loginWarning');
                document.getElementById('loader').classList.add('hidden');
            }
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

});