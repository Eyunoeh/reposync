<?php
session_start();

if (isset($_SESSION['log_user_type'])){
    header('Location: dashboard.php');
}


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

    <title>Login</title>
</head>
<body  class="bg-slate-100 grid place-items-center">
<main class="h-screen mx-auto flex  justify-center items-center sm:flex-row overflow-y-auto ">
    <img id="login_img" class="hover:cursor-pointer lg:block hidden object-fit h-[32rem] w-full sm:w-auto md:w-[720px] border border-black " src="assets/reposync%20bg%204.png" alt="Cookie Image">
    <div class="bg-white card h-[90vh] w-full  sm:w-96 sm:h-[32rem] bg-transparent text-neutral-content border-none md:border rounded-none">
        <div class="card-body flex flex-col justify-center items-center lg:border-black lg:border">
            <div class="text-center">
                <h2 class="text-2xl text-black font-bold">Welcome</h2>
                <h2 class="text-2xl text-black font-bold">Login to your account</h2>
            </div>
            <section class="overflow-y-auto max-h-[20rem] scroll-smooth  mt-16">
                <form id="login-form">
                    <div class="flex flex-col gap-2.5 justify-center text-black">
                        <div class="flex flex-col gap-2">
                            <label class="input input-bordered flex items-center gap-2 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" /><path d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" /></svg>
                                <input type="email" class="grow bg-slate-50" name="log_email" placeholder="Email" />
                            </label>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="input input-bordered flex items-center gap-2 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z" clip-rule="evenodd" /></svg>
                                <input name="log_password"
                                       type="password" autocomplete="off" placeholder="Password" data-theme="light"
                                       class="grow bg-slate-50  " />
                            </label>

                        </div>
                        <a href="#" class="text-sm text-center text-info">Fogot password?</a>
                    </div>
                    <div class="card-actions flex-col flex items-center  w-full mt-10">
                        <button id="login-btn-submit" class="btn btn-success btn-outline mr-2 h-10 p-3 w-20">Login</button>

                        <a href="#" class="text-sm text-center text-info">Continue with Google?</a>

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
<script src="js/Users.js"></script>
<script src="js/login.js">
</script>

</html>