<?php
session_start();

/*if (isset($_SESSION['log_user_type'])){
    header('Location: dashboard.php');
}*/


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/cvsulogo-removebg-preview.png">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">

    <!--    <script src="https://kit.fontawesome.com/470d815d8e.js" crossorigin="anonymous"></script>
    -->

    <!--    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    -->    <script src="jquery/jquery-3.7.1.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <title>Account activation</title>
</head>
<body >

<div class="min-h-screen flex items-center justify-center w-full bg-gradient-to-r from-green-700 to-slate-50 p-4">
    <div class="bg-slate-50 shadow-2xl rounded-xl px-8 py-6 max-w-md w-full transform transition-all duration-300 hover:scale-[1.01] animate-fade-in">
        <form  id="" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700  mb-2">
                    Student Number
                </label>
                <input oninput="this.value = this.value.slice(0, 9)"
                        type="number"
                        id="studNumber"
                        name="studNumber"
                        class="text-slate-700 shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 bg-slate-100
         placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500
         transition-all duration-300 [appearance:textfield] [&::-webkit-inner-spin-button]:hidden [&::-webkit-outer-spin-button]:hidden"
                        placeholder="Ex: 2101×××××"
                        required
                />
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700  mb-2">
                    Email Address
                </label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        class="text-slate-700 shadow-sm rounded-lg w-full px-4 py-2.5 border border-gray-200 bg-slate-100
                        placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500
                      transition-all duration-300"
                        placeholder="your@email.com"
                        required
                >
            </div>
            <div id="verfication-code-input" class="">

            </div>

            <button
                    id="verification-btn-submit"
                    type="submit"
                    class="w-full flex justify-center text-white
                     py-3 px-4 btn btn-success">
                Verify Account
<!--                <span class="loading loading-dots loading-lg"></span>-->
            </button>
            <a href="login.php" class="text-info w-full text-xs flex justify-center
                     text-center">
               Go back to login page
            </a>
        </form>
    </div>
</div>



<div id="notifbox"  onclick="resetAlertBox(this.id)">

</div>

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
<script src="js/register.js">
</script>

<script>





</script>

</html>