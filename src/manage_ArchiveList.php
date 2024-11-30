<?php session_start();
if (!isset($_SESSION['log_user_type']) or $_SESSION['log_user_type'] !== 'admin'){
    header('Location: index.php');
    exit();
}

$routeVal =  ['NarrativeReports'=>
    'NarrativeReports',

    'Users'=>'Users'];
$route = $_GET['route'];
if (!isset($_GET['route']) or !in_array($route,$routeVal)){
    header('Location: dashboard.php');
    exit();
}
$documentTitle = "";
if ($route === 'NarrativeReports'){
    $documentTitle = 'Archived Narrative Reports';
}else {
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

    <title><?= $documentTitle ?></title>
</head>


<body  class="min-h-screen bg-slate-200">
<div class="overflow-y-hidden h-[100vh] relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white">
    <div class="relative flex flex-col min-w-0 break-words rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent ">
            <a href="dashboard.php" class="btn btn-outline font-bold text-slate-700">
                <i class="fa-solid fa-circle-left"></i> Dashboard
            </a>
        </div>
        <div class="px-9 pt-5 mb-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent ">
            <div class="w-50">
                <select id="totalRecDis" onchange="<?php echo $routeVal[$route] === 'Users' ? 'renderArchiveUsers();' : 'renderArchiveNarratives();'; ?>
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
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'ArhiveTable')">
            </div>

        </div>
        <div class="block  px-9 overflow-auto h-[70vh] xl:h-[70vh]">
            <table id="ArhiveTable" class="w-full my-0 border-neutral-200 text-sm" >
                <thead class="align-bottom z-20">
                <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" id="archiveThRow">


                </tr>
                </thead>
                <tbody id="ArchiveTbody" class="text-center text-slate-600">
                <tr class="border-b border-dashed last:border-b-0 p-3">

                </tr>
                </tbody>
            </table>

        </div>
        <div class="flex justify-center gap-5" id="tablePager">


            <button id="prevBtn" onclick="<?php echo $routeVal[$route] === 'Users' ? 'renderArchiveUsers();' : 'renderArchiveNarratives();'; ?> prevPage(this.id, 'nextBtn')" class="btn-neutral btn-sm btn font-semibold">Prev</button>
            <div class="text-center">
                <span id="pageInfo">Page 1</span>
            </div>
            <button id="nextBtn" onclick="<?php echo $routeVal[$route] === 'Users' ? 'renderArchiveUsers();' : 'renderArchiveNarratives();'; ?> nextPage(this.id, 'prevBtn')" class="btn-neutral btn-sm btn font-semibold">Next</button>
        </div>
    </div>


</div>
<dialog id="unarchiveModal" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[80vw] absolute top-10 sm:w-[30rem] max-h-[35rem] flex flex-col text-slate-700">
        <div class="card-title sticky w-full">
            <h3 class="font-bold text-center text-lg p-5"
                id="noteText">Are you sure you want to archive this student narrative report?</h3>
        </div>
        <div class="p-4 w-full flex justify-evenly">
            <a id="unarchiveLink" class="btn btn-warning w-1/4"
               onclick="closeModalForm('unarchiveModal'); UnarchiveData($(this).attr('data-archive_id'));">
                Unarchive
            </a>
            <a class="btn btn-info w-1/4" onclick="closeModalForm('unarchiveModal')">Close</a>
        </div>
    </div>
</dialog>

<div id="notifBox" onclick="resetAlertBox(this.id)">

</div>
<script >

    document.addEventListener('DOMContentLoaded', async function (){
        const route = new URLSearchParams(window.location.search).get('route');
        let  endpoint;
        let tableHeadRow;
        if (route === 'NarrativeReports'){
            renderArchiveNarratives();
        }else if (route === 'Users'){
             renderArchiveUsers()

        }


    })
</script>
<script src="js/Datatables.js" ></script>

<script src="js/ArchiveList.js"></script>
<script src="js/buttons_modal.js"></script>
</body>
</html>
