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
        alert("Please fill in all fields");
        return;
    }
    if (!emailRegex.test(formData.get('log_email'))){
        alert('Input the right format');
        return;
    }
    if (formData.get('log_password').length < 8){
        alert('minimum of 8 characters');
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
                alert('Incorrect email or password');
                document.getElementById('loader').classList.add('hidden');
            }
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

});