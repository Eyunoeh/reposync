document.addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    let  endpoint = ''
    if (formData.get('verificationCode') === null){
        endpoint = '../accountRecoveryAjax.php?action=verifyUserAccount';
    }else {
        endpoint = '../accountRecoveryAjax.php?action=verifyAccountOTP';
    }
    if (formData.get('user_password') !== null && formData.get('user_confPass') !== null){
        endpoint = '../accountRecoveryAjax.php?action=PasswordChange';
        if (formData.get('user_password') !== formData.get('user_confPass')){
            Alert('notifbox', 'Passwords do not match', 'warning')
            return;
        }
    }
    const response = await $.ajax({
        url: endpoint,
        method: 'POST',
        data: Object.fromEntries(formData.entries()),
        dataType: 'json'
    });
    let callResponse = response.response;

    if (callResponse === 1){

        Alert('notifbox', response.message, 'success')


        if (response.message === 'OTP sent to your email'){
            let otpInp = `    <label for="email" class="block text-sm font-medium text-slate-700  mb-2">
                    Verification Code
                </label>
                <input oninput="this.value = this.value.slice(0, 6)"
                        type="number"
                        id="verificationCode"
                        name="verificationCode"
                        class="text-slate-700 shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 bg-slate-100
         placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500
         transition-all duration-300 [appearance:textfield] [&::-webkit-inner-spin-button]:hidden [&::-webkit-outer-spin-button]:hidden"
                        placeholder="Enter verification code"
                        required
                />`


            $('#verfication-code-input').html(otpInp);
            $('#verification-btn-submit').html(`Verify code`);
        }else if (response.message === 'OTP verified successfully. Please enter your new password.') {
            let newPassInp = `         
                    <label for="password" class="block text-sm font-medium  text-slate-700 mb-2">
                    New Password
                </label>
                <div class="flex items-center w-full h-full space-x-2 bg">
                    <input
                            type="password"
                            id="user_password"
                            autocomplete="off"
                            name="user_password"
                            class="text-slate-700 shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 bg-slate-100
               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500
               transition-all duration-300"
                            placeholder="Enter your password"
                            required
                    >
                    <!-- Eye Icon -->
                    <div onclick="togglePasswordVisibility('user_password','togglePasswordIcon_user_password')" class=" cursor-pointer text-slate-500">
                        <i id="togglePasswordIcon_user_password" class="fa fa-eye"></i> <!-- Font Awesome icon -->
                    </div>
                </div>`
            let confpass = `         
                       <label for="password" class="block text-sm font-medium  text-slate-700 mb-2">
                    Confirm Password
                </label>
                <div class="flex items-center w-full h-full space-x-2 bg">
                    <input
                            type="password"
                            id="user_confPass"
                            autocomplete="off"
                            name="user_confPass"
                            class="text-slate-700 shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 bg-slate-100
               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500
               transition-all duration-300"
                            placeholder="Enter your password"
                            required
                    >
                    <!-- Eye Icon -->
                    <div onclick="togglePasswordVisibility('user_confPass', 'togglePasswordIcon_user_confPass')" class=" cursor-pointer text-slate-500">
                        <i id="togglePasswordIcon_user_confPass" class="fa fa-eye"></i> <!-- Font Awesome icon -->
                    </div>
                </div>`
            let hiddenInp = `    
                <input type="hidden" id="email" name="email" value="${response.email}">
                <input type="hidden" id="otp" name="verificationCode" value="${response.otp}">`
            $('#newpass-input').html(newPassInp);
            $('#confpass-input').html(confpass);
            $('#hidden-input').html(hiddenInp);
            $('#verification-btn-submit').html(`Change Password`);

            $('#verfication-code-input').empty(confpass);
            $('#email-input').empty(confpass);
        }else {
            window.location.href = 'login.php';
        }


    }else {
        Alert('notifbox', response.message, 'error')

        if (response.message !== 'Invalid OTP' || response.message !== 'Passwords do not match'){
            window.location.href = 'Account_Activation.php';
        }

    }

})

function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

