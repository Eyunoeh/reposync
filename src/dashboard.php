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
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">
<!--    <script src="https://kit.fontawesome.com/470d815d8e.js"crossorigin="anonymous"></script>
-->    <!--    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
-->    <script src="jquery/jquery-3.7.1.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../node_modules/chart.js/dist/chart.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>





    <title>Dashboard</title>
</head>
<body  class="min-h-screen bg-white ">

<main class="flex h-[100vh] ">
    <div class="sidebar sm:inline-block z-20 absolute sm:relative h-full scroll-smooth   p-2 w-[300px] min-w-[300px] overflow-y-hidden hover:overflow-y-auto  text-center bg-green-700" >
        <div class=" text-gray-100 text-xl bg-green-700 transition-all ">
            <div onclick="" class="btn btn-ghost btn-circle btn-sm foc absolute sm:hidden  right-0 top-0 ">
                <a href="#" class="grid place-items-center mt-1"><i class="fas fa-times"></i></a>
            </div>
            <div class="p-2.5 mt-1 flex items-center">
                <div class="w-8 ">
                    <img src="assets/cvsulogo-removebg-preview.png" />
                </div>



                <h1 class=" text-white font-extrabold text-[15px] ml-3">Insight</h1>
                <i class="bi bi-x cursor-pointer ml-28 lg:hidden" onclick="//openSidebar()"></i>
            </div>
            <div class="text-start">
                <p class="pl-2.5 text-slate-50 text-xs font-semibold " id="side_tabName"></p>
            </div>
            <div class="my-2 bg-gray-600 h-[1px]"></div>
        </div>

        <a href="index.php">
            <div class=" p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 active:bg-slate-200 text-white">
                    <i class="fa-solid fa-house"></i>
                <span class="text-[15px] ml-4  font-bold">Home</span>

            </div>
        </a>
        <div id="dashboard"
             onclick="dashboard_tab(this.id); " class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-gauge"></i>
            <span class="text-[15px] ml-4  font-bold">Dashboard</span>
        </div>

        <?php if ( isset($_SESSION['log_user_type'])  && $_SESSION['log_user_type'] == 'adviser'):?>

            <div id="dashBoardWeeklyReport" onclick="dashboard_tab(this.id,);" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
                <i class="fa-solid fa-book-journal-whills"></i>
                <span class="text-[15px] ml-4  font-bold">Students Weekly Journal</span>
            </div>
        <?php endif;?>


        <div id="announcement" onclick="dropdown('AnnouncementSubmenu')" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-slate-200 hover:text-slate-700  text-white">
            <i class="fa-solid fa-bullhorn"></i>
            <div class="flex justify-between w-full items-center">
                <span class="text-[15px] ml-4  font-bold">Announcement</span>
            </div>
            <i class="fa-solid fa-chevron-down"></i>
        </div>
        <div id="AnnouncementSubmenu" class="ani text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" >


            <h1 onclick="dashboard_tab(this.id); " id="adviserNotes" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                <i class="fa-solid fa-envelopes-bulk"></i>
                Post Announcement
            </h1>

            <?php
            if ( isset($_SESSION['log_user_type'])  && $_SESSION['log_user_type'] == 'admin'):
            ?>
                <h1 onclick="dashboard_tab(this.id) ;" id="notesReq" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                    <i class="fa-solid fa-list"></i>
                    Announcement List
                </h1>

                <h1 onclick="dashboard_tab(this.id); " id="schedule&Act" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                <i class="fa-regular fa-calendar-days"></i>
                 Activities & Schedule
            </h1>
            <?php endif;?>

        </div>
        <?php

        if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] == 'adviser'):

        ?>
        <div id="dashboard_ReviewUploadNarrative" onclick="dashboard_tab(this.id)" class="dashboard_tab p-2.5 mt-3 flex items-start rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-paper-plane"></i>
            <span class="text-[15px] ml-4  font-bold">Submitted Narrative Reports</span>
        </div>
        <?php endif;?>
        <div id="dashboard_narrative" onclick="dashboard_tab(this.id)" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-book"></i>
            <span class="text-[15px] ml-4  font-bold">Narrative Reports</span>
        </div>



        <?php

        if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] == 'admin'):

        ?>
        <div id="dashBoardProg_n_Section" onclick="dashboard_tab(this.id); " class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
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
            <h1 onclick="dashboard_tab(this.id); " id="stud_list" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                Student
            </h1>
            <?php
            if ( isset($_SESSION['log_user_type'])  && $_SESSION['log_user_type'] == 'admin'):
            ?>
                <h1 onclick="dashboard_tab(this.id); " id="adv_list" class="dashboard_tab w-full cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                    Advisers
                </h1>


            <?php endif?>
            <h1 onclick="dashboard_tab(this.id);" id="profile" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                Profile
            </h1>
            <h1 onclick="dashboard_tab(this.id);" id="accountInfo" class="dashboard_tab cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                Account
            </h1>
        </div>
        <?php
        if ( isset($_SESSION['log_user_type'])  && $_SESSION['log_user_type'] == 'admin'):
        ?>
        <div onclick="dashboard_tab(this.id);" id="account_archived" class="dashboard_tab p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-recycle"></i>
            <span class="text-[15px] ml-4  font-bold">Archive</span>
        </div>
        <?php endif?>
        <a href="logout.php" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-error hover:text-slate-700  text-white">
            <i class="fa-solid fa-right-from-bracket"></i>
            <h1 ><span class="text-[15px] ml-4  font-bold">Logout</span></h1>
        </a>

    </div>
    <button class=" btn btn-ghost sm:hidden inline-block   left-5 top-5">&#9776;</button>

    <section id="dashboard_main_content" class="flex gap-2 flex-col   w-full overflow-auto">


    </section>

</main>
<div id="notifBox" onclick="resetAlertBox(this.id)">

</div>


<script src="js/Datatables.js"></script>
<script src="js/buttons_modal.js"></script>
<script src="js/Print.js"></script>
<script src="js/Users.js"></script>
<script src="js/dashboardContent.js"></script>
<script src="js/dashboard.js"></script>


<script src="js/manageStudent.js"></script>
<script src="js/manageAdviser.js"></script>
<script src="js/manageNarrativeReport.js"></script>
<?php if ($_SESSION['log_user_type'] === 'admin'):?>
<script src="js/Admin.js"></script>
<?php elseif  ($_SESSION['log_user_type'] === 'adviser'):?>
<script src="js/Adviser.js"></script>
<?php endif;?>
<script src="js/Chart.js"></script>
<script src="js/Announcement.js"></script>

<script src="js/admin_adviserAjaxRequest.js"></script>


</body>