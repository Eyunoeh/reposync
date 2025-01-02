

<?php

session_start();

if (!isset($_SESSION['log_user_type'])){
    header('Location: 404.php');
}








?>


<!DOCTYPE html>
<html lang="en" data-theme="light">
<head data-theme="light">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">
    <link rel="icon" type="image/x-icon" href="assets/cvsulogo-removebg-preview.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


    <title>Narrative Reports</title>
</head>


<body  class="min-h-screen bg-slate-200">
<div class="overflow-y-hidden h-[100vh] relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white">
    <div class="relative flex flex-col min-w-0 break-words rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent ">
            <a href="<?=$_SESSION['log_user_type'] == 'student'? 'index.php?page=narratives':'dashboard.php'?>" class="btn btn-outline font-bold text-slate-700">
                <?=$_SESSION['log_user_type'] == 'student'? '<i class="fa-solid fa-house"></i> Home':'<i class="fa-solid fa-circle-left"></i> Dashboard'?>
                </a>
            <?php if ($_SESSION['log_user_type'] === 'admin'):?>
                <button onclick="printNarrativeReportsList()" class="btn btn-neutral bg-slate-500 border-none text-slate-100">Export data <i class="fa-solid fa-file-export"></i></button>
            <?php endif;?>
        </div>
        <div class="px-9 pt-5 mb-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent ">
            <div class="w-50">
                <select id="totalRecDis" onchange="dashboard_student_NarrativeReports();
                    renderPage_lim(this.value,'nextBtn', 'prevBtn' )" class=" select-sm select select-bordered bg-slate-100 ">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>
                <span class="text-xs">Entries per page</span>
            </div>
            <div class=" w-[40%]">

                <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
            </div>

        </div>


        <div class="block  px-9 overflow-auto h-[70vh] xl:h-[70vh]">

                <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200 text-sm" >
                    <thead class="align-bottom z-20" id="narrativeListThead">

                    </thead>
                    <tbody id="narrativeReportsTableBody" class="text-center text-slate-600">

                    </tbody>
                </table>
            <div id="tableLoader" class="flex justify-center items-center">
                <span class="loading loading-spinner loading-lg"></span>
            </div>
        </div>

        <div class="flex justify-center gap-5" id="tablePager">


            <button id="prevBtn" onclick="dashboard_student_NarrativeReports(); prevPage(this.id, 'nextBtn')" class="btn-neutral btn-sm btn font-semibold">Prev</button>
            <div class="text-center">
                <span id="pageInfo">Page 1</span>
            </div>
            <button id="nextBtn" onclick="dashboard_student_NarrativeReports(); nextPage(this.id, 'prevBtn')" class="btn-neutral btn-sm btn font-semibold">Next</button>
        </div>
    </div>
</div>





<dialog id="archiveNarrativeModal" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[80vw] absolute top-10 sm:w-[30rem] max-h-[35rem] flex flex-col text-slate-700">
        <div class="card-title sticky">
            <h3 class="font-bold text-center text-lg p-5">Are you sure you want to archive this student narrative report?</h3>
        </div>
        <div class="p-4 w-full flex justify-evenly">
            <a id="archiveNarrative" class="btn btn-warning w-1/4"
               onclick="closeModalForm('archiveNarrativeModal'); archiveNarrative($(this).attr('data-narrative'));">
                Archive
            </a>
            <a class="btn btn-info w-1/4" onclick="closeModalForm('archiveNarrativeModal')">Close</a>
        </div>
    </div>
</dialog>

<div id="notifBox" onclick="resetAlertBox(this.id)"></div>



<script src="js/Datatables.js"></script>
<script src="js/Users.js"></script>

<script src="js/viewNarrativeReport.js"></script>
<?php if ($_SESSION['log_user_type'] === 'admin'):?>
    <script src="js/Print.js"></script>
<?php endif;?>
<script src="js/ArchiveList.js"></script>
<script src="js/buttons_modal.js"></script>


</body>