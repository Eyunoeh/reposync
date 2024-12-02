<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <link rel="icon" type="image/x-icon" href="assets/cvsulogo-removebg-preview.png">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">

    <!--    <script src="https://kit.fontawesome.com/470d815d8e.js" crossorigin="anonymous"></script>
    -->

    <!--    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    -->
    <script src="jquery/jquery-3.7.1.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">

    <title>Home</title>
</head>
<body  class="min-h-screen bg-slate-100">

    <?php include('home_nav.php') ?>
    <main class=" mx-auto overflow-hidden" id="mainContent">

    </main>
    <script src="js/main.js" defer></script>
    <?php if (isset($_SESSION['log_user_type'])) {
        $user_types = ['adviser', 'admin', 'student'];?>
        <div id="notifBox" onclick="resetAlertBox(this.id)">

        </div>

        <script src="js/Datatables.js"></script>


        <script src="js/buttons_modal.js"></script>
        <script src="js/manageNarrativeReport.js"></script>


    <?php

        if ($_SESSION['log_user_type'] === $user_types[2]){

            ?>


        <script src="js/student.js"></script>
        <script src="js/weeklyJournalcomment.js"></script>


            <?php
        }
    } ?>
    <script src="js/Users.js"></script>
    <script>

    </script>





</body>
</html>