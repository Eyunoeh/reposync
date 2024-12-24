<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
session_start();
?>
<?php if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] === 'admin'):?>
<center class="mb-5">
    <div class=" p-10 flex flex-col justify-center shadow-lg rounded items-center w-[80%] flex-grow  max-h-[60vh]">
        <canvas id="NarrativeReportChart" class="w-2/3 h-2/3"></canvas>
    </div>
    <div class=" p-10 shadow-lg flex flex-col rounded justify-center items-center w-[80%] flex-grow  max-h-[60vh]">
        <canvas id="UserChart" class="w-2/3 h-2/3"></canvas>
    </div>
</center>

<?php elseif ( (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] === 'adviser')):?>

    <div class="flex pt-3 gap-5 ml-3 h-full flex-wrap justify-center  text-slate-700">

        <div onclick="dashboard_tab(this.id)" id="pendingNarrativeReqCount" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="pendingUploadNarrativeReport">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans  text-sm   ">Unread submitted narrative report</h1>
        </div>
        <div onclick="dashboard_tab(this.id)" id="declinedNarrativeReqCount" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="declinedUploadNarrativeReport">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans text-sm">Revision submitted narrative report</h1>
        </div>
        <div onclick="dashboard_tab(this.id) ;" id="dshbweeklyReport" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="UnreadStudWeeklyReport">
                <span class="loading loading-spinner loading-lg"></span>

            </h1>
            <h1 class="font-sans  text-sm" >Unread Weekly Reports</h1>
        </div>

        <!--<div onclick="dashboard_tab(this.id); " id="adviserNotesCard" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="pendingNoteCount">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans  text-sm">Pending Notes</h1>
        </div>-->
        <div onclick="dashboard_tab(this.id);" id="stud_list" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow">
            <h1 class="font-bold font-sans text-[4rem]" id="totalAdvisory">
                <span class="loading loading-spinner loading-lg"></span>
            </h1>
            <h1 class="font-sans  text-sm">Total active student</h1>
        </div>
    </div>
    <center class="mb-5">
        <div class="p-4 flex flex-col  justify-center shadow-lg rounded items-center w-[80%] flex-grow  max-h-[60vh]">

            <canvas id="NarrativeReportChart" class="w-2/3 h-2/3 "></canvas>
        </div>
    </center>


<?php endif;?>








