
<?php
session_start();
if (!isset($_SESSION['log_user_id'])){
    header("Location: 404.php");
}
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
include '../functions.php';
include '../DatabaseConn/databaseConn.php';
?>

<section class="w-full min-h-screen flex justify-center  mt-2">

    <div class="w-full max-w-7xl mx-auto p-5 rounded-lg shadow-lg bg-white min-h-[600px]">
        <div class=" p-3 shadow flex w-full items-center <?php echo $_SESSION['log_user_type'] === 'student' ? 'justify-between' :  'justify-center'?> justify-end">

            <h1 class="font-bold text-2xl text-slate-700 font-sans">Narrative reports tree view</h1>

            <?php if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] === 'student'):?>
                <a class="btn btn-ghost text-info font-semibold btn-neutral  cursor-pointer
                    " id="newNarrative" onclick="openModalForm('NarrativeReportmodal'); getSubmittedNarratives()"><u>View submitted narrative report</u></a>
            <?php endif;?>
        </div>

        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto ">
                <div id="treeview" class="p-4 text-lg grid gap-5">

                </div>

            </div>
        </div>
    </div>
</section>

<?php if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] === 'student'):?>

    <dialog id="NarrativeReportmodal" class="modal bg-black bg-opacity-40">
        <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[40rem]  flex flex-col text-slate-700">
            <div  class="card-title sticky justify-center flex p-2">
                <a class=" btn btn-sm btn-circle btn-ghost absolute top-2 right-2" onclick="closeModalForm('NarrativeReportmodal')">✕</a>
                <h3 class="r font-bold text-center text-lg mb-5">Submitted narrative report</h3>

            </div>
            <div class="p-4 flex justify-center flex-col items-center">
                <table id="studuploadedNarratives" class="w-full my-0 border-neutral-200 text-sm">
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0  text-secondary-dark bg-slate-200 rounded text-neutral">
                        <th class="p-3 text-start">#</th>
                        <th class="p-3 text-start">Semester</th>
                        <th class="p-3 text-start">Academic Year</th>
                        <th class="p-3 text-start">Status</th>
                        <th class="p-3 text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody id="studuploadedNarrativesTableBody" class="text-slate-600">
<!--                    <tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">1</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">First</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">2020-2021</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">Converted</span>
                        </td>

                        <td class="p-3 text-end">
                            <span class="font-semibold cursor-pointer text-light-inverse text-md/normal break-words"><i class="fa-regular fa-eye"></i>
                               </span>
                        </td>
                    </tr>
                    <tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">2</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">Second</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">2021-2022</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">Pending</span>
                        </td>

                        <td class="p-3 text-end">
                            <span class="font-semibold cursor-pointer text-light-inverse text-md/normal break-words"><i class="fa-solid fa-circle-info"></i></span>
                        </td>
                    </tr>
                    <tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">3</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">Summer</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">2023-2024</span>
                        </td>
                        <td class="p-3 text-start break-words w-1/4">
                            <span class="font-semibold text-light-inverse text-md/normal text-justify">
                                Decline<br>Reason: <span class="text-warning text-sm">
                                    asdasdasda asdasd asdasd asdasdasda asdasd asdasd
                                    asdasdasda asdasd asdasd </span></span>
                        </td>

                        <td class="p-3 text-end">
                            <span class="font-semibold cursor-pointer text-light-inverse text-md/normal break-words"><i class="fa-solid fa-circle-info"></i></span>
                        </td>
                    </tr>-->
                    </tbody>
                </table>

                <div id="tableNoRes">
                    <span class="loading loading-spinner loading-lg"></span>
                </div>
                <div class="mt-10" id="SubmitnewBtnContainer">
                </div>
            </div>
        </div>
    </dialog>
    <dialog id="NarrativeReportmodalForm" class="modal bg-black bg-opacity-40">
        <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[40rem]  flex flex-col text-slate-700">
            <div  class="card-title sticky justify-between flex p-2">
                <a class=" btn btn-sm  btn-ghost"
                   onclick="openModalForm('NarrativeReportmodal');
                   closeModalForm('NarrativeReportmodalForm')"><i class="fa-solid fa-circle-arrow-left"></i>Go back</a>
                <a class=" btn btn-sm  btn-ghost btn-circle" onclick="
                   closeModalForm('NarrativeReportmodalForm')">✕</a>

            </div>
            <div class="p-4 flex justify-center flex-col">
                <form id="NarrativeReportForm">
                    <div class="flex flex-col gap-8">

                        <div class="flex justify-center items-center flex-wrap sm:flex-nowrap">
                            <input name="narrativeReportPDF" required type="file"
                                   accept="application/pdf"
                                   class=" text-sm text-black file:mr-4 file:py-2
                                   file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold
                            file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                        </div>
                        <input type="hidden" name="NarraActType" id="NarraActType">
                        <input type="hidden" name="narrative_id" id="narrative_id">

<!--                        <button class="btn btn-neutral btn-outline ">Submit</button>
-->
                        <p id="narrativeSubmitLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                        <div id="NarrativeSubmit" class="flex justify-center w-full">
                            <button id="submit_btn" class="btn btn-neutral btn-outline w-1/2" >Submit</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </dialog>
<?php endif;?>



