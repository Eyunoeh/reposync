<?php
session_start();
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['log_user_type']) || $_SESSION['log_user_type'] !== 'student') {
    return;
}
include '../DatabaseConn/databaseConn.php';
$user_id = $_SESSION['log_user_id'];

?>
<section class="w-full min-h-screen flex justify-center mt-2">

    <div class="w-full max-w-7xl mx-auto p-5 rounded-lg  shadow-lg bg-white min-h-[600px]">
        <div class="px-9  flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent">
            <div class="w-[40%]">
                <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                focus:outline-none focus:shadow-outline" id="weeklyReportSearch" type="text" placeholder="Search"
                       onkeyup="handleSearch('weeklyReportSearch',getVisibleTableId());">
            </div>

            <div class="flex justify-evenly gap-5">
                <button class=" font-semibold btn btn-neutral  " id="stud-weekly-rpt-btn" onclick="change_stud_table();$('#noResultRow').remove();">View logs</button>
                <div class="tooltip  tooltip-bottom" data-tip="Upload weekly journal">
                    <a class="btn btn-neutral btn-circle " id="newWeeklyReport" onclick="openModalForm('newReport')"><i class="fa-solid fa-plus"></i></a>
                </div>
            </div>
        </div>
        <div class="px-9 flex justify-end w-full">
        </div>
        <div class="block py-8 pt-6 px-9">
            <div id="table_card" class="overflow-y-auto overflow-x-hidden h-[90vh] scroll-smooth">
                <table class="w-full my-0  border-neutral-200 " id="weeklyReportTable" >
                    <thead class="align-bottom  z-10">
                    <tr class="font-semibold text-[0.95rem] 0 sticky top-0  text-secondary-dark bg-slate-200 rounded">
                        <th class="p-3  " onclick="sortTable(0, 'weeklyReportTable')" >#<span class="sort-icon text-xs"></th>
                        <th class="p-3  " onclick="sortTable(1, 'weeklyReportTable')">Week<span class="sort-icon text-xs"></th>
                        <th class="p-3 " onclick="sortTable(2, 'weeklyReportTable')">Status<span class="sort-icon text-xs"></th>
                        <th class="p-3 " onclick="sortTable(3, 'weeklyReportTable')">View Comments<span class="sort-icon text-xs"></th>
                        <th class="p-3 text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody id="Weeklyreports" class=" text-center ">




                    </tbody>
                </table>
                <table class="w-full my-0   border-neutral-200 hidden" id="logsTable" >
                    <thead class="align-bottom  z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0  text-secondary-dark bg-slate-200 rounded">
                        <th class="p-3 " onclick="sortTable(0, 'logsTable')">Week<span class="sort-icon text-xs"></th>
                        <th class="p-3" onclick="sortTable(1, 'logsTable')">Activity Date<span class="sort-icon text-xs"></th>
                        <th class="p-3" onclick="sortTable(2, 'logsTable')">Type<span class="sort-icon text-xs"></th>
                    </tr>
                    </thead>
                    <tbody class=" text-center " id="logsTable_body">


                    </tbody>
                </table>

                <div id="tableNoRes" class="text-center">
                    <span class="loading loadi ng-spinner loading-lg"></span>

                </div>
            </div>
        </div>
    </div>
</section>

<dialog id="comments" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] h-[100vh] lg:h-[37rem]  flex flex-col text-slate-700 overflow-auto">
        <div class="card-title sticky">

            <button class=" btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('comments')">✕</button>

            <h3 class="font-bold text-lg text-center text-black  top-0  p-5">Report Comments</h3>
        </div>
        <div id="comment_body" class="card bg-slate-50 hover:cursor-pointer transition-all w-full flex-grow h-screen  card-body flex flex-col text-slate-700 overflow-auto">
            <!--<div class="grid place-items-center">
                <div class="flex justify-end items-end">
                    <p class="py-4 px-2 border bg-slate-200 rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lor
                        em ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea
                        ex facilis magni modi, molestias perferendis placeat quam quasi, qui
                        ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="assets/prof.jpg" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-1 w-full justify-end">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/prof.jpg" alt="report comment">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/prof.jpg" alt="report comment">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/prof.jpg" alt="report comment">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/prof.jpg" alt="report comment">

                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-[10px] text-slate-400 text-center">2:30pm</p>
                <p class="text-[10px] text-slate-400 text-center">4/16/2024</p>
            </div>
            <div class="grid place-items-center">
                <div class="flex justify-end items-end">
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="assets/prof.jpg" />
                        </div>
                    </div>
                    <p class="py-4 px-2 border bg-slate-200 rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lor
                        em ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea
                        ex facilis magni modi, molestias perferendis placeat quam quasi, qui
                        ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>

                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-[10px] text-slate-400 text-center">2:30pm</p>
                <p class="text-[10px] text-slate-400 text-center">4/16/2024</p>
            </div>
            <div class="grid place-items-center">
                <div class="flex justify-end items-end">
                    <p class="py-4 px-2 border bg-slate-200 rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lor
                        em ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea
                        ex facilis magni modi, molestias perferendis placeat quam quasi, qui
                        ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="assets/prof.jpg" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/open-book-clipart-07.png" alt="report comment">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/open-book-clipart-07.png" alt="report comment">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/open-book-clipart-07.png" alt="report comment">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="assets/open-book-clipart-07.png" alt="report comment">

                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-[10px] text-slate-400 text-center">2:30pm</p>
                <p class="text-[10px] text-slate-400 text-center">4/16/2024</p>
            </div>-->


        </div>


        <div class="m-3 shadow-2xl rounded border bg-opacity-70 ">
            <div  id="chatBox" class="flex   justify-start items-center p-2  gap-2 ">
                <textarea id="revision_comment" name="revision_comment" class="sm:w-auto w-full flex-grow textarea  max-h-24 textarea-bordered  bg-slate-100" placeholder="Type here"></textarea>

                <div class="relative">
                    <label title="Click to upload" for="commentAttachment"
                           class="cursor-pointer flex items-center
                               gap-4 px-6 py-4 before:border-gray-400/60
                                hover:before:border-gray-300 group before:bg-gray-100
                                 before:absolute before:inset-0 before:rounded-3xl before:border
                                  before:border-dashed before:transition-transform before:duration-300
                                  hover:before:scale-105 active:duration-75 active:before:scale-95">
                        <div class="w-max relative max-w-10 flex flex-wrap" id="imgAttachment">
                            <img class="w-5" src="assets/clip-svgrepo-com.svg" alt="file upload icon" width="512" height="512">
                        </div>

                    </label>
                    <input onchange="displaySelectedcommentAttachment()" hidden type="file" name="commentAttachment[]" id="commentAttachment" accept="image/png, image/jpeg" multiple>
                </div>
                <button id="sendCommentbtn" onclick="giveComment(this.getAttribute('file_id')); get_WeeklyReports()" class="btn btn-md btn-circle btn-ghost"><i class="fa-regular fa-paper-plane"></i></button>

            </div>
        </div>


    </div>
</dialog>
<dialog id="img_modal" class="modal bg-black  bg-opacity-40">
    <div class="card  w-[100vw] sm:w-[55rem] h-[100vh]
          flex flex-col text-slate-700 overflow-auto">
        <div class="card-title sticky">

            <button class=" btn btn-sm btn-circle absolute right-2 top-2" onclick="closeModalForm('img_modal')">✕</button>

        </div>
        <div class="card-body flex justify-center items-center max-h-[100vh] overflow-auto">
            <img id="viewImage" src="" class="h-full sm:h-[75vh] object-scale-down">
        </div>
    </div>
</dialog>



<dialog id="newReport" class="modal bg-black bg-opacity-40">
    <div class="modal-box bg-white">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('newReport')">✕</button>
        <h3 class="font-bold text-center text-lg mb-5">Upload weekly report</h3>
        <form id="addWeeklyReportForm">
            <div class="flex flex-col gap-8">
                <label class="form-control flex justify-center w-full ">
                    <div class="label">
                        <span class="label-text text-slate-700">Week</span>
                    </div>
                    <div class="flex gap-2 justify-between items-center w-full">
                        <input  type="date" required name="startWeek"  class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs"  />
                        <p class="text-center items-center "> to </p>
                        <input  type="date" required name="endWeek" class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs"  />
                    </div>
                </label>
                <input name="weeklyReport" required type="file" accept="application/pdf" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                <button class="btn btn-neutral btn-outline ">Submit</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="resubmitReport" class="modal bg-black bg-opacity-40">
    <div class="modal-box bg-white ">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('resubmitReport')">✕</button>
        <h3 class="font-bold text-center text-lg mb-5">Resubmit report</h3>
        <form id="resubmitReportForm">
            <div class="flex flex-col gap-8">
                <label class="form-control flex justify-center w-full ">
                    <div class="label">
                        <span class="label-text text-slate-700">Week</span>
                    </div>
                    <div class="flex gap-2 justify-between items-center w-full">
                        <input  type="date" required name="startWeek"  class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs"  />
                        <p class="text-center items-center "> to </p>
                        <input  type="date" required name="endWeek" class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs"  />
                    </div>
                </label>
                <input name="resubmitReport" required type="file" accept="application/pdf" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                <input type="hidden" name="file_id" value="">
                <button class="btn btn-neutral btn-outline ">Submit</button>
            </div>
        </form>
    </div>
</dialog>

