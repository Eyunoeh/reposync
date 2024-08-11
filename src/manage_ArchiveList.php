<?php session_start();
if (!isset($_SESSION['log_user_type']) or $_SESSION['log_user_type'] !== 'admin'){
    header('Location: index.php');
    exit();
}
if (!isset($_GET['route']) or !in_array($_GET['route'], ['NarrativeReports', 'Users'])){
    header('Location: dashboard.php');
    exit();
}
$documentTitle = "";
if ($_GET['route'] === 'NarrativeReports'){
    $documentTitle = 'Archived Narrative Reports';
}else{
    $documentTitle = 'Archived Users';
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
    <script src="js/Datatables.js" ></script>

    <title><?= $documentTitle ?></title>
</head>


<body  class="min-h-screen bg-slate-200">
<div class="overflow-y-hidden h-[100vh] relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white">
    <div class="relative flex flex-col min-w-0 break-words rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent ">

            <a href="dashboard.php" class="btn btn-outline font-bold text-slate-700"><i class="fa-solid fa-house"></i> Dashboard</a>

            <form class="flex justify-start w-[40%]">

                <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch(this.id, 'ArhiveTable')">
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-[80vh]">
                <table id="ArhiveTable" class="w-full my-0 border-neutral-200 text-sm" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" id="archiveThRow">


                    </tr>
                    </thead>
                    <tbody id="ArchiveTbody" class="text-center text-slate-600">
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start w-[10rem]">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">123123123</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">BSIT</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">OJT Adviser Name</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">2020 -2021</span>
                        </td>
                        <td class="p-3  flex gap-2 justify-end">
                            <a href="" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                            <a href="" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr class="w-full p-2">

    <dialog id="ArhiveModal" class="modal bg-black  bg-opacity-40">
        <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[38rem]  flex flex-col text-slate-700">
            <div  class=" card-title sticky ">
                <a class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('ArhiveModal')">✕</a>
                <?php  if($_GET['route'] === 'NarrativeReports'):?>
                <h3 class="font-bold text-center text-lg  p-5">Archived student narrative report</h3>
                    <div data-tip="Download PDF" class="tooltip tooltip-bottom">

                        <a id="dlLink" href="" target="_blank" class="btn btn-circle  hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2 text-info"><i class="fa-solid fa-download"></i></a>
                    </div>
                <?php  elseif($_GET['route'] === 'Users'):?>
                    <h3 class="font-bold text-center text-lg  p-5">Archived User</h3>

                <?php  endif;?>

            </div>


            <div class="p-4" id="ArhiveForm">


            </div>
            <p id="loader_narrative_update" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>

            <div id="editNarrativeBtn" class="flex justify-center m-3 gap-2">
                <a id="retrieve_btn" class="btn btn-warning btn-outline" >Retrieve</a>
            </div>
        </div>
    </dialog>
</div>
<script src="js/ArchiveList.js"></script>
<script src="js/buttons_modal.js"></script>
</body>
</html>
