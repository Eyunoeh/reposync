<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>

<div class="flex gap-2 ml-3 mt-3 flex-wrap xl:justify-center justify-evenly lg:justify-center h-full items-center text-slate-700 ">
    <div onclick="dashboard_tab(this.id)" id= "dshbContentLinkActStud" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500  hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow-xl">
        <h1 class="font-bold font-sans text-[4rem]">60</h1>
        <h1 class="font-sans text-[25px]">Active Students</h1>
    </div>
    <div onclick="dashboard_tab(this.id)" id= "dshbContentLinkActAdv" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow-xl">
        <h1 class="font-bold font-sans text-[4rem]">13</h1>
        <h1 class="font-sans text-[25px]">Active Adviser</h1>
    </div>
    <div onclick="dashboard_tab(this.id)" id= "dshbContentLinkNarratives" class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-100 text-center grid place-items-center shadow-xl">
        <h1 class="font-bold font-sans text-[4rem]">300</h1>
        <h1 class="font-sans text-[20px]">Narrative Reports</h1>
    </div>
</div>
<div class="flex gap-2 ml-3 mt-3 flex-wrap xl:justify-center justify-evenly lg:justify-center h-full items-center text-slate-700 ">

    <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300  bg-slate-50 text-center grid place-items-center shadow-xl">
        <h1 class="font-bold font-sans text-[4rem]">23</h1>
        <h1 class="font-sans text-[25px]">Archive Student</h1>
    </div>
    <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300  bg-slate-50 text-center grid place-items-center shadow-xl">
        <h1 class="font-bold font-sans text-[4rem]">60</h1>
        <h1 class="font-sans text-[25px]">Archive Adviser</h1>
    </div>


    <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32 transition duration-500 hover:cursor-pointer hover:bg-slate-300 bg-slate-50 text-center grid place-items-center shadow-xl">
        <h1 class="font-bold font-sans text-[4rem]">60</h1>
        <h1 class="font-sans text-[20px]">Archive Narrative Report</h1>
    </div>
</div>
