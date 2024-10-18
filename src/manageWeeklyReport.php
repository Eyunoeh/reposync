<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
include '../DatabaseConn/databaseConn.php';
include '../functions.php';
$secret_key ='TheSecretKey#02';





session_start();
?>

<div class="overflow-y-hidden relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
        <form class="flex w-full justify-between">

            <div class="w-50">
                <select id="totalRecDis" onchange="renderWeeklyJournaltbl();
                    renderPage_lim(this.value,'nextBtn', 'prevBtn' )" class=" select-sm select select-bordered bg-slate-100 ">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>
                <span class="text-xs">Entries per page</span>
            </div>
            <div class="w-[40%]">
                <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searcbox" type="text" placeholder="Search" onkeyup="handleSearch('searcbox', 'AdvisoryWeeklyReportTbl')">
            </div>

        </form>
    </div>
    <div class="   mx-5 h-[90vh] overflow-auto">
        <table id="AdvisoryWeeklyReportTbl" class="w-full my-0 border-neutral-200 text-sm" >
            <thead class="align-bottom  sticky top-0 z-20
            font-bold text-[0.95rem]  text-secondary-dark bg-slate-200 rounded text-neutral">
                <tr class="" >
                    <th onclick="sortTable(0, 'AdvisoryWeeklyReportTbl')" class="p-3 text-start cursor-pointer">School ID <span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(1, 'AdvisoryWeeklyReportTbl')" class="p-3 text-start cursor-pointer">Name <span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(2, 'AdvisoryWeeklyReportTbl')" class="p-3 text-start w-40 cursor-pointer">Company <span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(3, 'AdvisoryWeeklyReportTbl')" class="p-3 text-start w-40 cursor-pointer">Location <span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(4, 'AdvisoryWeeklyReportTbl')" class="p-3 text-center cursor-pointer">Last Activity <span class="sort-icon text-xs"></span></th>
                    <th class="p-3 text-end " colspan="9">Check Reports </th>
                </tr>
            </thead>
            <tbody id="AdvisoryWeeklyReportList" class="text-center text-slate-600">


            <!--<tr><td colspan="9">No Active / Assigned students found for this adviser.</td></tr>-->
            </tbody>
        </table>
        <div id="tableadvLoader" class="flex justify-center items-center">
            <span class="loading loading-spinner loading-lg"></span>
        </div>

    </div>


    <div class="flex justify-center gap-5">
        <button id="prevBtn" onclick="renderWeeklyJournaltbl(); prevPage(this.id, 'nextBtn')" class="btn-neutral btn-sm btn font-semibold">Prev</button>
        <div class="text-center">
            <span id="pageInfo">Page 1</span>
        </div>
        <button id="nextBtn" onclick="renderWeeklyJournaltbl(); nextPage(this.id, 'prevBtn')" class="btn-neutral btn-sm btn font-semibold">Next</button>
    </div>



</div>
