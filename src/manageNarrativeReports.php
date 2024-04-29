<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}


?>

<div class="px-9 pt-2 flex justify-end items-stretch flex-wrap  pb-0 bg-transparent">
    <button class="btn btn-neutral bg-slate-500 border-none text-slate-100" onclick="openModalForm('newNarrative')">New Narrative Report</button>

</div>
<div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-between">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                </div>
                <div class="w-[40%]">
                    <select class="w-full h-10 rounded bg-slate-50 font-semibold"  id="programFilter" onchange="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                        <option value="">Select Program</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSBM">BSBM</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-96">
                <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                        <th class="p-3 text-start ">Name</th>
                        <th class="p-3 text-start ">OJT adviser</th>
                        <th class="p-3 text-end ">Program</th>
                        <th class="p-3 text-end ">Section</th>
                        <th class="p-3 text-end ">Action</th>
                    </tr>
                    </thead>
                    <tbody id="narrativeReportsTableBody" class="text-center text-slate-600">
                        <tr class="border-b border-dashed last:border-b-0 p-3">
                            <td class="p-3 text-start">
                                <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                            </td>
                            <td class="p-3 text-start">
                                <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="font-semibold text-light-inverse text-md/normal">4A</span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="font-semibold text-light-inverse text-md/normal">BSIT</span>
                            </td>
                            <td class="p-3 text-center">
                                <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                                <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-error"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr class="w-full p-2">
    <!--
    <div class="flex justify-center pr-6 w-full">
        <div class="join grid grid-cols-2">
            <button id="prev-page" class="join-item btn btn-outline" onclick="prev_Page()">Previous page</button>
            <button id="next-page" class="join-item btn btn-outline" onclick="next_Page()">Next</button>
        </div>
    </div>
    -->
</div>
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
                    <button id="submit_btn" class="btn btn-success btn-outline " >Submit</button>
                </div>
            </form>
        </div>
        <p id="loader_narrative" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
    </div>
</dialog>

<dialog id="EditNarrative" class="modal card bg-black bg-opacity-40">
    <div class="modal-box bg-slate-50 min-h-[30rem]  h-[30rem] flex flex-col ">
        <div  class=" card-title sticky">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('EditNarrative')">✕</button>
            <h3 class="font-bold text-center text-lg mb-5">Edit Student Narrative Report</h3>
        </div>
        <div class="overflow-auto max-h-[22rem] card-body ">
            <form id="EditNarrativeReportsForm"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 text-slate-700">
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
                    <input type="hidden" name="narrative_id" value="">

                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text text-warning">Leave empty or replace existing</span>
                        </div>
                        <input name="final_report_file" accept="application/pdf" type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />

                    </label>
                    <button id="update_btn" class="btn btn-info btn-outline" >Update</button>
                    <button id="archive_btn" class="btn btn-error btn-outline" >Archive</button>
                </div>
            </form>
        </div>
        <p id="loader_narrative_update" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
    </div>
</dialog>


<script>


</script>