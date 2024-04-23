
<?php
session_start();
?>

<div class="flex flex-wrap  text-gray-700  ">
    <?php
    if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] != 'student'):
    ?>
    <div class="w-full max-w-full ">
        <div class="px-9 pt-5 flex justify-end items-stretch flex-wrap  pb-0 bg-transparent">

                <button class="btn btn-neutral bg-slate-500 border-none text-slate-100" onclick="openModalForm('newNarrative')">New Narrative Report</button>
                <dialog id="newNarrative" class="modal card bg-black bg-opacity-40">
                    <div class="modal-box bg-slate-50 min-h-[30rem]  h-[30rem] flex flex-col ">
                        <div  class=" card-title sticky">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('newNarrative')">✕</button>
                            <h3 class="font-bold text-center text-lg mb-5">Add Student Narrative Report</h3>
                        </div>
                        <div class="overflow-auto max-h-[22rem] card-body ">
                            <form id="narrativeReportsForm"  enctype="multipart/form-data">
                                <div class="flex flex-col gap-8">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex flex-col gap-2">
                                            <label class="font-bold text-sm">First Name</label>
                                            <input required name="first_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Type here...">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label class="font-bold text-sm">Last Name</label>
                                            <input required name="last_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Type here...">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label class="font-bold text-sm">School ID</label>
                                            <input required name="school_id" min="0" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="number" placeholder="Type here...">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label class="font-bold text-sm">Program</label>
                                            <input required name="program" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Type here...">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label class="font-bold text-sm">Section</label>
                                            <input required name="section" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Type here...">
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <label class="font-bold text-sm">OJT Adviser</label>
                                            <input required name="ojt_adviser" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Type here...">
                                        </div>
                                    </div>
                                    <input name="final_report_file" accept="application/pdf" required type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                                    <button id="submit_btn" class="btn btn-neutral btn-outline " >Submit</button>
                                </div>
                            </form>
                        </div>
                        <p id="loader_narrative" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                    </div>

                </dialog>
        </div>
        <?php
        endif;
        ?>
        <div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
            <div class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
                <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent
                 border-slate-300">
                    <form class="flex w-full justify-between">
                        <div class="w-[40%]">
                            <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Search">
                        </div>
                        <div class="w-[40%]">
                            <select class="w-full h-10 rounded bg-slate-50 font-semibold" name="stud_program">
                                <option>Select Program</option>
                                <option value="">BSIT</option>
                                <option>BSBM</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="block py-8 pt-6 px-9">
                    <div class="overflow-auto h-80">
                        <table class="w-full my-0  border-neutral-200">
                            <thead class="align-bottom  z-20">
                                <tr class="font-semibold text-[0.95rem] sticky top-0  z-20 text-secondary-dark bg-slate-200 rounded">
                                    <th class="p-3  ">Name</th>
                                    <th class="p-3 text-end">Program</th>
                                    <th class="p-3  text-end">View Report</th>
                                </tr>
                            </thead>
                            <tbody id="narrativeReports" class="text-center">
                            <tr class="border-b border-dashed last:border-b-0 p-3">
                                <td class="p-3  ">
                                    <span class="font-semibold text-light-inverse text-md/normal">Johny Doe</span>
                                </td>
                                <td class="p-3  text-end">
                                    <span class="font-semibold text-light-inverse text-md/normal">BSIT</span>
                                </td>
                                <td class="p-3  text-end">
                                    <a href="flipbook.php?student=sampletud " target="_blank" class="hover:cursor-pointer  mb-1  font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
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




