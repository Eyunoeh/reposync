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
    <link rel="icon" type="image/x-icon" href="assets/insightlogo1.png">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <title>Login</title>
</head>
<body >

<div class="min-h-screen flex items-center justify-center w-full bg-gradient-to-r from-yellow-400 to-green-700 p-4">
    <div class="bg-slate-50 shadow-2xl rounded-xl px-8 py-6 max-w-md w-full transform transition-all duration-300 hover:scale-[1.01] animate-fade-in">
        <h1 class="text-3xl font-bold text-center mb-8 text-slate-700 ">Login</h1>
        <form  id="login-form" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700  mb-2">
                    Email Address
                </label>
                <input
                        type="email"
                        id="email"
                        name="log_email"
                        class="text-slate-700 shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 bg-slate-100
                        placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500
                      transition-all duration-300"
                        placeholder="your@email.com"
                        required
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium  text-slate-700 mb-2">
                    Password
                </label>
                <div class="flex items-center w-full h-full space-x-2 bg">
                    <input
                            type="password"
                            id="password"
                            autocomplete="off"
                            name="log_password"
                            class="text-slate-700 shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 bg-slate-100
               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500
               transition-all duration-300"
                            placeholder="Enter your password"
                            required
                    >
                    <!-- Eye Icon -->
                    <div onclick="togglePasswordVisibility()" class=" cursor-pointer text-slate-500">
                        <i id="togglePasswordIcon" class="fas fa-eye"></i> <!-- Font Awesome icon -->
                    </div>
                </div>



            </div>
            <div class="flex justify-between">
                <a href="#"
                   class="inline-block mt-2 text-sm
                   text-slate-700
                   hover:text-blue-900
                  transition-colors duration-300">
                    Forgot Password?
                </a>
                <button class="btn btn-sm btn-neutral btn-ghost text-slate-700">
                    <i class="fa-brands fa-google "></i>
                    <span>Sign in with Google</span>
                </button>
            </div>



            <button
                    id="login-btn-submit"
                    type="submit"
                    class="w-full flex justify-center
                     py-3 px-4 btn btn-success">
                Login
            </button>
            <a href="index.php" class="text-info w-full text-xs flex justify-center
                     text-center">
                Go back home
            </a>
        </form>
    </div>
</div>







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


<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>


</body>
<script src="js/buttons_modal.js"></script>
<script src="js/Users.js"></script>
<script src="js/login.js">
</script>

<script>





</script>

</html>