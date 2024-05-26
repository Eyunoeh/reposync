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

<div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-start">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searcbox" type="text" placeholder="Search" onkeyup="handleSearch('searcbox', 'NotesReqTbL')">
                </div>
            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-96">
                <table id="NotesReqTbL" class="w-full my-0 border-neutral-200 text-sm" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                        <th class="p-3 text-start ">Adviser name</th>
                        <th class="p-3 text-start ">Title</th>
                        <th class="p-3 text-start ">Description</th>
                        <th class="p-3 text-end ">Status</th>
                        <th class="p-3 text-end ">Note posted</th>
                        <th class="p-3 text-end ">Action</th>

                    </tr>
                    </thead>
                    <tbody id="NotesReq" class="text-center text-slate-600">
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                        </td>

                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">Male</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">4A</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">BSIT</span>
                        </td>
                         <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">5/12/2024</span>
                        </td>
                        <td class="p-3 text-end">
                            <a href="#" class="hover:cursor-pointer mb-1
                            font-semibold transition-colors duration-200
                            ease-in-out text-lg/normal text-secondary-inverse
                            hover:text-accent"><i class="fa-solid fa-circle-info" onclick="openModalForm('AdviserNoteReq')"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<dialog id="AdviserNoteReq" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[40rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky" id="NoteTitle">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" id="closeAnnouncementForm" onclick="closeModalForm('AdviserNoteReq'); ">✕</button>
            <h3 class="font-bold text-center text-lg  p-2">Adviser student note</h3>
        </div>
        <div id="status_Box">


        </div>

        <div class="p-4">
            <form id="AdvFormStat"  >
                <input type="hidden" name="actionType" value="new" id="action_type">
                <input type="hidden" value="" name="announcementID" id="announcementID">
                <div class="flex flex-col gap-8 mb-2 overflow-auto ">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Update status</span>
                        </div>
                        <select name="NoteStat" class="select select-bordered">
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approve</option>
                            <option value="Declined">Declined</option>
                            <option value="Hidden">Hidden</option>
                        </select>
                    </label>
                    <div class="flex justify-start gap-2">
                        <label class="form-control w-full ">
                            <div class="label">
                                <span class="label-text text-slate-700 font-bold">Reason</span>
                            </div>
                            <textarea required  name="reason" class="textarea textarea-error w-full" rows="5" cols="50" placeholder="Type here"></textarea>
                        </label>
                    </div>
                    <input type="hidden" name="file_id" value="">
                </div>
                <p id="notes" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                <div id="noteSubmit" class="flex justify-center m-3">
                    <button id="submit_btn" class="btn btn-success bg-opacity-40 btn-outline w-1/2" >OK</button>
                </div>
            </form>
        </div>
    </div>
</dialog>