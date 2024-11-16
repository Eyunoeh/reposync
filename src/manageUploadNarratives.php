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
    <div class="relative flex flex-col min-w-0 break-words   rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between  flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <div class="w-[40%]">
                <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searcbox" type="text" placeholder="Search" onkeyup="handleSearch('searcbox', 'narrativeReportsTable')">
            </div>
  <!--          <div class=" flex justify-end items-stretch flex-wrap  pb-0 bg-transparent">
                <button class="btn btn-success btn-outline btn-md text-slate-100" onclick="openModalForm('newNarrative')">New Narrative Report</button>
            </div>-->
        </div>
        <div class="m-5 h-[85vh] overflow-auto">
            <table id="narrativeReportsTable" class="
            w-full my-0 border-neutral-200 text-sm" >
                <thead class="align-bottom  sticky top-0 z-20
            font-semibold text-[0.95rem]  text-secondary-dark bg-slate-200 rounded text-neutral">
                <tr >
                    <th class="p-3 text-start ">School ID</th>
                    <th class="p-3 text-start">Student name</th>
                    <th class="p-3 text-start">Status</th>
                    <th class="p-3 text-start">Sem & AY</th>
                    <th class="p-3 text-start">Date uploaded</th>
                    <th class="p-3 text-start">Convert Status</th>
                    <th class="p-3 text-end ">Action</th>
                </tr>
                </thead>
                <tbody id="narrativeReportsReqTableBody" class="text-center text-slate-600">
                <tr>
                    <td colSpan="9">
                        <span class="loading loading-spinner loading-lg"></span>
                    </td>
                </tr>
                </tbody>
            </table>



        </div>
    </div>
</div>

<dialog id="EditNarrativeReq" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[38rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('EditNarrativeReq')">✕</button>
            <h3 class="font-bold text-center text-lg  p-5">Review student narrative report</h3>

            <div data-tip="Download PDF" class="tooltip tooltip-bottom">
                <a id="dlLink" href="" target="_blank" class="btn btn-circle  hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2 text-info"><i class="fa-solid fa-download"></i></a>
            </div>
        </div>
        <div class="p-4">
            <form id="UpdSubNarrativeReport"  enctype="multipart/form-data">

                <label class="form-control w-full" id="SelectreqStatuses">
                    <div class="label">
                        <span class="label-text font-semibold">Update status</span>
                    </div>
                    <select id="UploadStat" name="UploadStat" class="select select-bordered">
                        <option disabled value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Declined">With Revision</option>
                    </select>
                </label>

                <div class="flex justify-evenly gap-2" id="declineUploadReason">

                </div>

                <input type="hidden" name="narrative_id" id="narrative_id">
                <p id="loader_narrative_update" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>



        <div id="editNarrativeBtn" class="flex justify-center mt-3 gap-2 w-full">
            <button id="update_btn" class="btn btn-info btn-outline w-1/4" >Save</button>
        </div>
                <div id="upd_SubNarrativ_note" class="flex justify-center m-3">
                </div>

            </form>
        </div>
    </div>
</dialog>
