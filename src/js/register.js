
document.addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    let  endpoint = ''
    if (formData.get('verificationCode') === null){
        endpoint = '../accountActivationAjax.php?action=verifyStudentNum';
    }else {
        endpoint = '../accountActivationAjax.php?action=verifyOTP';
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
        }else {
            window.location.href = 'Account_Activation.php';
        }



    }else {
        Alert('notifbox', response.message, 'error')

        if (response.message !== 'Invalid OTP'){
            window.location.href = 'Account_Activation.php';
        }

    }

})
