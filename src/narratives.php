
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
        <div class="px-9 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-between">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                </div>

                <?php if (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] === 'student'):?>
                <a class="btn btn-ghost text-info font-semibold btn-neutral  cursor-pointer
                    " id="newNarrative" onclick="openModalForm('NarrativeReportmodal'); getSubmittedNarratives()"><u>Submit narrative report</u></a>
                <?php endif;?>
            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-full">
                <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200 text-sm">
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0  text-secondary-dark bg-slate-200 rounded text-neutral">
                        <th class="p-3 text-start">Code</th>
                        <th class="p-3 text-start">Program</th>

                        <th class="p-3 text-end">View</th>
                    </tr>
                    </thead>
                    <tbody id="narrativeReportsTableBody" class="text-slate-600">
                    <?php
                    $sql = "SELECT * FROM  program";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($row = $res->fetch_assoc()){
                        echo '<tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">'.$row['program_code'].'</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">'.$row['program_name'].'</span>
                        </td>
          
                        <td class="p-3 text-end">
                            <a href="dashboardViewnarrativeReports.php?program='.$row['program_code'].'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                                               ';


                    }

                    ?>
                    </tbody>
                </table>
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
                    <tr class="border-b border-dashed last:border-b-0">
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
                    </tr>
                    </tbody>
                </table>
                <div class="mt-10">
                    <button class="btn btn-neutral btn-outline" onclick="closeModalForm('NarrativeReportmodal');openModalForm('NarrativeReportmodalForm')">Submit new</button>
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
                        <div class="flex justify-evenly gap-2 flex-wrap sm:flex-nowrap">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Semester</span>
                                </div>
                                <select required name="semester"  class="select bg-slate-100  select-bordered w-full max-w-xs" >
                                    <option>First</option>
                                    <option>Second</option>
                                    <option>Summer</option>
                                </select>

                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Academic year</span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <input  type="number" required name="startYear" oninput="this.value = this.value.slice(0, 4)" class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs" placeholder="0000" />
                                    <p class="text-center items-center font-bold text-lg"> - </p>
                                    <input  type="number" required name="endYear" oninput="this.value = this.value.slice(0, 4)" class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs" placeholder="0000" />
                                </div>

                            </label>

                        </div>
                        <div class="flex justify-center items-center flex-wrap sm:flex-nowrap">
                            <input name="narrativeReportPDF" required type="file"
                                   accept="application/pdf"
                                   class=" text-sm text-black file:mr-4 file:py-2
                                   file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold
                            file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                        </div>
                        <input type="hidden" name="stud_user_id" value="<?php echo $_SESSION['log_user_id']?>">

                        <button class="btn btn-neutral btn-outline ">Submit</button>



                    </div>
                </form>
            </div>
        </div>
    </dialog>
<?php endif;?>



