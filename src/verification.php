<?php

include_once '../functions.php';
$secret_key = 'TheSecretKey#02'; //id encryption password dont remove
/*
$email = 'adsasdasdasd';
$encryptedData = encrypt_data($email, $secret_key);
echo $encryptedData;
echo '<br>';
echo decrypt_data($encryptedData, $secret_key);*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/cvsulogo-removebg-preview.png">

    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <title>Verification</title>
</head>
<body  class="bg-slate-100 grid place-items-center">
<main class="h-screen mx-auto flex  justify-center items-center sm:flex-row overflow-y-auto ">
    <div class="bg-white card h-[90vh] w-full  sm:w-96 sm:h-[32rem] bg-transparent text-neutral-content border-none md:border shadow-xl rounded-lg">
        <div class="card-body flex flex-col justify-center items-center ">
            <div class="text-center">
                <h2 class="text-2xl text-black font-bold">Account Verification</h2>
            </div>
            <section class="overflow-y-auto max-h-[20rem] scroll-smooth  mt-16">
                <form id="login-form">
                    <div class="flex flex-col gap-2.5 justify-center text-black">

                        <div class="flex flex-col gap-2">
                            <label class="input input-bordered flex items-center gap-2 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z" clip-rule="evenodd" /></svg>
                                <input type="password" class="grow" name="log_password" placeholder="OTP" />
                            </label>
                        </div>
                        <a href="#" class="text-sm text-center text-info">Resend OTP</a>

                    </div>

                    <div class="card-actions flex-col flex items-center  w-full mt-10">
                        <button id="login-btn-submit" class="btn btn-success btn-outline mr-2 h-10 p-3 w-20">Verify</button>
                    </div>


                </form>
            </section>

        </div>
    </div>
</main>
<div id="loader" class="hidden absolute h-[100vh] w-full grid place-items-center bg-black bg-opacity-35">
    <span class="loading loading-dots loading-lg text-white"></span>
</div>
<dialog id="loginWarning"  class="modal bg-black  bg-opacity-40 ">
    <div class="card bg-warning w-[80vw] absolute top-10 sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky justify-center">
            <h3 class="font-bold text-center text-lg  p-5" id="loginNotiftext"></h3>
        </div>
        <div class="p-4 w-full flex justify-center">
            <button class="btn  btn-neutral  w-1/4 " onclick="closeModalForm('loginWarning')">OK</button>
        </div>
    </div>
</dialog>

</body>
<script src="js/buttons_modal.js"></script>
<!--<script src="js/login.js">-->
</script>

</html>
