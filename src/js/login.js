document.getElementById('login_img').addEventListener('click', function() {
    window.location.href = 'index.php';
});
document.getElementById('login-btn-submit').addEventListener('click', function (){
    let formData = new FormData(document.getElementById("login-form"));
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
    if (!emailRegex.test(formData.get('email'))){
        alert('Input the right format');
        return;
    }
    if (formData.get('password').length < 8){
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
            if (response == 1) {
                setTimeout(function() {

                    document.getElementById('loader').classList.add('hidden');
                }, 2500);

                window.location.href = 'index.php';
            } else {
                console.log(response);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

});