<?php
session_start();
if (!isset($_SESSION['log_user_type']) || $_SESSION['log_user_type'] !== 'student') {
    header("Location: index.php");
    exit();
}
?>
<div class="grid place-items-center  text-gray-700 h-[95%]">
    <div class="w-full max-w-full ">
        <div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
            <div class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
                <!-- card header -->
                <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent">
                    <form class="flex w-[40%] justify-between hidden sm:inline-flex">
                        <div class="w-full">
                            <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Search">
                        </div>
                    </form>

                    <div class="flex justify-evenly ">
                        <button class="h font-semibold btn btn-ghost" id="stud-weekly-rpt-btn" onclick="change_stud_table()">View logs</button>
                        <button class="btn btn-neutral btn-outline" onclick="openModalForm('newReport')">New Report</button>
                    </div>
                </div>
                <div class="px-9 flex justify-end w-full">
                </div>
                <div class="block py-8 pt-6 px-9">
                    <div id="table_card" class="overflow-auto h-80 scroll-smooth">
                        <table class="w-full my-0  border-neutral-200 " id="weeklyReportTable" >
                            <thead class="align-bottom  z-20">
                                <tr class="font-semibold text-[0.95rem] sticky top-0  z-20 text-secondary-dark bg-slate-200 rounded">
                                    <th class="p-3  ">Week</th>
                                    <th class="p-3 ">Status</th>
                                    <th class="p-3 ">View Comments</th>
                                    <th class="p-3 ">Action</th>
                                </tr>
                            </thead>
                            <tbody class=" text-center ">

                            <tr class="border-b border-dashed last:border-b-0">

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">1</span>
                                </td>

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">Approve</span>
                                </td>
                                <td class="p-3 pr-0 " >
                                    <div class="indicator hover:cursor-pointer" onclick="openModalForm('comments')">
                                        <span class="indicator-item badge badge-neutral"  data-journal-comment-id="3" id="journal_comment_2">5</span>
                                        <a class="font-semibold text-light-inverse text-md/normal"><i class="fa-regular fa-comment"></i></a>
                                    </div>
                                </td>
                                <td class="p-3 pr-0  ">
                                    <div  class="tooltip tooltip-bottom"  data-tip="View">
                                        <a href="NarrativeReportsPDF/Narrative%20Report%20Format.pdf" target="_blank" class=" text-light-inverse text-md/normal mb-1 hover:cursor-pointer font-semibold
                                    transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"  ><i class="fa-regular fa-eye"></i></a>
                                    </div>
                                    <div class="tooltip tooltip-bottom" data-tip="Resubmit">
                                        <a class="text-light-inverse text-md/normal mb-1 hover:cursor-pointer font-semibold
                                transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"  onclick="openModalForm('resubmitReport')"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </div>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        <table class="w-full my-0   border-neutral-200 hidden" id="logsTable " >
                            <thead class="align-bottom  z-20">
                                <tr class="font-semibold text-[0.95rem] sticky top-0  z-20 text-secondary-dark bg-slate-200 rounded">
                                    <th class="p-3 ">Week</th>
                                    <th class="p-3">Activity Date</th>
                                    <th class="p-3">Type</th>
                                </tr>
                            </thead>
                            <tbody class=" text-center">

                            <tr class="border-b border-dashed last:border-b-0">

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">1</span>
                                </td>

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">4/7/2024</span>
                                </td>
                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">Resubmit</span>
                                </td>

                            </tr>
                            <tr class="border-b border-dashed last:border-b-0">

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">1</span>
                                </td>

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">4/5/2024</span>
                                </td>
                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">Upload</span>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<dialog id="comments" class="modal bg-black bg-opacity-40">
    <div class="modal-box bg-slate-50 min-h-[30rem] w-[80%] h-[30rem]">
        <div class="card-title sticky">
            <form method="dialog">
                <button class=" btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('comments')">✕</button>
            </form>
            <h3 class="font-bold text-lg text-center text-black  top-0 bg-slate-50 py-2">Report Comments</h3>
        </div>
        <div class="overflow-auto max-h-[22rem] card-body">
            <div class="grid place-items-center">

                <p class="py-4 px-2 border rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea ex facilis magni modi, molestias perferendis placeat quam quasi, qui ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                <div class="flex">
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                </div>

            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-sm text-black text-center">2:30pm</p>
                <p class="text-sm text-black text-center">4/16/2024</p>
            </div>
            <div class="grid place-items-center">
                <p class="py-4 px-2 border rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea ex facilis magni modi, molestias perferendis placeat quam quasi, qui ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                <div class="flex">
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                    <a href="comments_img/Capture.PNG" target="_blank"><img class="min-h-[5rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment"></a>
                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-sm text-black text-center">4:30pm</p>
                <p class="text-sm text-black text-center">4/16/2024</p>
            </div>

        </div>
    </div>
</dialog>



<dialog id="newReport" class="modal bg-black bg-opacity-40">
    <div class="modal-box bg-white">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('newReport')">✕</button>


        <h3 class="font-bold text-center text-lg mb-5">Add Report</h3>
        <form id="addWeeklyReportForm">
            <div class="flex flex-col gap-8">
                <input name="file_sch_id" required type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                <input type="hidden" name="newWeeklyReport" value="submitSuccess">
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
                <input name="file_sch_id" required type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                <input type="hidden" name="resubmitReport" value="resubmitSuccess">
                <button class="btn btn-neutral btn-outline ">Submit</button>
            </div>
        </form>
    </div>
</dialog>

