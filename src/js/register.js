const roleSelect = document.querySelector('select[name="user_role"]');
const programSelect = document.getElementById('program');
const regFormInput = document.getElementById('reg_form');
const form = document.getElementById('signup-form');

roleSelect.addEventListener('change', function() {
    if (roleSelect.value === "Adviser") {
        programSelect.style.display = 'none';
        regFormInput.classList.add('hidden');
    } else {
        programSelect.style.display = 'block';
        regFormInput.classList.remove('hidden');
    }
});

document.getElementById('reg_img').addEventListener('click', function() {
    window.location.href = 'index.php';
});

document.getElementById("sign-up-submit-btn").addEventListener("click", function(event) {
    let formData = new FormData(document.getElementById("signup-form"));
    let isValid = true;

    formData.forEach(function(value, key) {
        // Check if the value is a string before calling trim()
        if (typeof value === 'string' && !value.trim()) {
            isValid = false;
            return;
        }
    });

    // Skip validation for reg_form input if the role is "Adviser"
    if (roleSelect.value !== "Adviser") {
        let fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(function(fileInput) {
            if (!fileInput.files || fileInput.files.length === 0) {
                isValid = false;
            } else {
                // Check file type
                let allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                let uploadedFile = fileInput.files[0];
                if (!allowedExtensions.exec(uploadedFile.name)) {
                    isValid = false;
                }
            }
        });
    }

    if (!isValid) {
        alert("Please fill in all fields and select JPEG or PNG files for both SchoolID and Registration Form.");
        return;
    }

    if (formData.get('password') !== formData.get('conf_password')) {
        alert("Passwords do not match.");
        return;
    }

    $.ajax({
        url: 'ajax.php?action=signUp',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            document.getElementById('loader').classList.remove('hidden');
            if (response == 1) {
                setTimeout(function() {
                    document.getElementById('signup_modal').classList.remove('hidden');
                    document.getElementById('loader').classList.add('hidden');
                    // Show modal success
                }, 2500);
            } else {
                console.log(response);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});



