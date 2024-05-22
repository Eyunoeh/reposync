<?php
session_start();
if (!isset($_SESSION['log_user_type']) or $_SESSION['log_user_type'] == 'student'){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <link rel="icon" type="image/x-icon" href="assets/cvsulogo-removebg-preview.png">
    <script src="https://kit.fontawesome.com/470d815d8e.js"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">
    <title>Dashboard</title>
</head>
<body  class="min-h-screen bg-white ">

<main class="flex h-[100vh] ">
    <div class="sidebar hidden sm:inline-block scroll-smooth  p-2 w-[300px] min-w-[300px] overflow-y-hidden hover:overflow-y-auto  text-center bg-green-700" >
        <div class=" text-gray-100 text-xl bg-green-700 transition-all ">
            <div class="p-2.5 mt-1 flex items-center">
                <div class="avatar">
                    <div class="w-8 rounded">
                        <img src="assets/cvsulogo-removebg-preview.png" />
                    </div>
                </div>
                <h1 class="font-bold text-gray-200 text-[15px] ml-3">Reposync</h1>
                <i class="bi bi-x cursor-pointer ml-28 lg:hidden" onclick="//openSidebar()"></i>
            </div>
            <div class="my-2 bg-gray-600 h-[1px]"></div>
        </div>

        <a href="index.php">
            <div class=" p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 active:bg-slate-200 text-white">

                    <i class="fa-solid fa-house"></i>
                <span class="text-[15px] ml-4  font-bold">Home</span>

            </div>
        </a>
        <div id="dashboard" onclick="dashboard_tab(this.id)" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-gauge"></i>
            <span class="text-[15px] ml-4  font-bold">Dashboard</span>
        </div>
        <div id="dashBoardWeeklyReport" onclick="dashboard_tab(this.id)" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-brands fa-font-awesome"></i>
            <span class="text-[15px] ml-4  font-bold">Students Weekly Report</span>
        </div>

        <div id="announcement" onclick="dropdown('AnnouncementSubmenu')" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-slate-200 hover:text-slate-700  text-white">
            <i class="fa-solid fa-bullhorn"></i>
            <div class="flex justify-between w-full items-center">
                <span class="text-[15px] ml-4  font-bold">Announcement</span>
            </div>
            <i class="fa-solid fa-chevron-down"></i>
        </div>
        <div id="AnnouncementSubmenu" class="ani text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" >
            <h1 onclick="dashboard_tab(this.id)" id="adviserNotes" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                <i class="fa-regular fa-note-sticky"></i>
                 Notes
            </h1>
            <?php
            if ( isset($_SESSION['log_user_type'])  && $_SESSION['log_user_type'] == 'admin'):
            ?>
            <h1 onclick="dashboard_tab(this.id)" id="schedule&Act" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                <i class="fa-regular fa-calendar-days"></i>
                 Activities & Schedule
            </h1>
            <?php endif;?>
        </div>
        <div id="dashboard_narrative" onclick="dashboard_tab(this.id)" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-book"></i>
            <span class="text-[15px] ml-4  font-bold">Narrative Reports</span>
        </div>

        <?php

        if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] == 'admin'):

        ?>
        <div id="dashBoardProg_n_Section" onclick="dashboard_tab(this.id)" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-graduation-cap"></i>
            <span class="text-[15px] ml-4  font-bold">Prog-Yr-Sec</span>
        </div>
        <?php endif;?>



        <div class="my-4 bg-gray-600 h-[1px]"></div>

        <div onclick="dropdown('UserSubmenu')" id="userTab" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-slate-200 hover:text-slate-700  text-white" >
            <i class="fa-solid fa-user"></i>
            <div class="flex justify-between w-full items-center">
                <span class="text-[15px] ml-4  font-bold">Users</span>
            </div>
            <i class="fa-solid fa-chevron-down"></i>
        </div>
        <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" id="UserSubmenu">
            <h1 onclick="dashboard_tab(this.id);" id="stud_list" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                Student
            </h1>
            <?php
            if ( isset($_SESSION['log_user_type'])  && $_SESSION['log_user_type'] == 'admin'):
            ?>
                <h1 onclick="dashboard_tab(this.id);" id="adv_list" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                    Advisers
                </h1>

                <h1 onclick="dashboard_tab(this.id);" id="account_archived" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                    Archived
                </h1>
            <?php endif?>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-error hover:text-slate-700  text-white">
            <i class="fa-solid fa-right-from-bracket"></i>
            <a href="logout.php"><span class="text-[15px] ml-4  font-bold">Logout</span></a>
        </div>

    </div>
    <section id="dashboard_main_content" class="flex gap-2 flex-col   w-full overflow-auto">

    </section>

</main>

<script src="js/dashboard.js"></script>

<script src="js/admin_adviserAjaxRequest.js"></script>

<script src="js/buttons_modal.js"></script>
<script src="js/Datatables.js"></script>
</body>