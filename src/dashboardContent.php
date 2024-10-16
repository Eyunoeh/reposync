<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
session_start();
?>
<?php if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] === 'admin'):?>
<div class=" flex flex-col">
    <div class="flex gap-5 ml-3 my-3 flex-wrap justify-evenly sm:justify-start items-center text-slate-700">

        <div onclick="dashboard_tab(this.id); get_dashBoardnotes()" id="adviserNotesReq" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="pendingNoteCount">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans ">Pending Adviser Notes</h1>
        </div>
        <div onclick="dashboard_tab(this.id)" id="dshbuploadNarrativeReq" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="pendingUploadNarrativeReport">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans ">Pending Narrative Report Upload</h1>
        </div>
    </div>
    <div class="flex-grow">
        <canvas id="myChart" class="w-2/3 h-2/3"></canvas>
    </div>
</div>
<?php elseif ( (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] === 'adviser')):?>

    <div class="flex gap-5 ml-3 my-3 h-full flex-wrap justify-start  text-slate-700">

        <div onclick="dashboard_tab(this.id)" id="pendingNarrativeReqCount" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="pendingUploadNarrativeReport">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans ">Pending Upload Narrative Report</h1>
        </div>
        <div onclick="dashboard_tab(this.id)" id="declinedNarrativeReqCount" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="declinedUploadNarrativeReport">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans ">Declined Upload Narrative Report</h1>
        </div>
        <div onclick="dashboard_tab(this.id)" id="dshbweeklyReport" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="UnreadStudWeeklyReport">
                <span class="loading loading-spinner loading-lg"></span>

            </h1>
            <h1 class="font-sans " >Unread Weekly Reports</h1>
        </div>

        <div onclick="dashboard_tab(this.id); get_dashBoardnotes()" id="adviserNotesCard" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="pendingNoteCount">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans ">Pending Notes</h1>
        </div>
        <div onclick="dashboard_tab(this.id)" id="stud_list" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="totalAdvisory">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans ">Total active student</h1>
        </div>
    </div>

<?php endif;?>








