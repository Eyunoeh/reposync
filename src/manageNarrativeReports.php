<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}

include '../DatabaseConn/databaseConn.php';
function getTotalNarrativeReports($program) {
    include '../DatabaseConn/databaseConn.php';

    $sql = "SELECT COUNT(*) AS total FROM narrativereports WHERE program = ? and file_status = 'OK'";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $program);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total;
    } else {
        return "Error preparing the SQL statement: " . $conn->error;
    }
}

session_start()
?>

<div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-start">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                </div>

            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-full">
                <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200 text-sm">
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral">
                        <th class="p-3 text-start">Code</th>
                        <th class="p-3 text-start">Program</th>
                        <th class="p-3 text-end">Total narratives</th>
                        <th class="p-3 text-center">View</th>
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
                            <span class="font-semibold text-light-inverse text-md/normal">'.getTotalNarrativeReports($row['program_code']).'</span>
                        </td>
                        <td class="p-3 text-center">
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
</div>

<dialog id="newNarrative" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[40rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('newNarrative')">✕</button>
            <h3 class="font-bold text-center text-lg  p-5">Add student narrative report</h3>
        </div>
        <div class="p-4">
            <form id="narrativeReportsForm"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-auto h-[25rem]">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">First name</span>
                                </div>
                                <input type="text" required name="first_name" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Last name</span>
                                </div>
                                <input type="text" required name="last_name" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">School ID number <span class="text-warning"> (Must be unique)</span></span>
                                </div>
                                <input type="number" min="0" oninput="this.value = this.value.slice(0, 9)" required name="school_id" placeholder="XXXXXXXX" maxlength="8" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Sex</span>
                                </div>
                                <div class="flex justify-start gap-2">
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Male</label>
                                        <input type="radio" name="stud_Sex" value="Male" class="radio bg-gray-300" checked />
                                    </div>
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Female</label>
                                        <input type="radio" name="stud_Sex" value="Female" class="radio bg-gray-300" />
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select required name="program" class="select select-bordered w-full bg-slate-100 ">
                                    <option selected disabled>Select program</option>
                                    <?php
                                    $sql = "SELECT * FROM  program";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option >'.$row['program_code'].'</option>
                                               ';

                                    }

                                    ?>
                                </select>

                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Section</span>
                                </div>
                                <select required name="section" class="select select-bordered w-full bg-slate-100 ">
                                    <option>Select Section</option>
                                    <?php
                                    $sql = "SELECT * FROM  section";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option>'.$row['section'].'</option>';
                                    }
                                    ?>
                                </select>                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT Adviser</span>
                                </div>
                                <select name="ojt_adviser" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select adviser</option>
                                    <?php
                                    $adv_option_query = "SELECT ui.*, acc.*
                                 FROM tbl_user_info ui
                                 INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
                                 WHERE ui.user_type IN ('admin', 'adviser') AND acc.status = 'active'";
                                    $result = $conn->query($adv_option_query);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['user_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>N/A</option>";
                                    }
                                    ?>


                                </select>
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Narrative Report PDF</span>
                                </div>
                                <input name="final_report_file" accept="application/pdf" required type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                            </label>
                        </div>


                    </div>
                </div>
                <p id="loader_narrative" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>

                <div id="newNarrativeSubmitbtn" class="flex justify-center m-3">
                    <button id="submit_btn" class="btn btn-success btn-outline w-1/2" >Submit</button>
                </div>

            </form>
        </div>
    </div>
</dialog>



<dialog id="EditNarrative" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[40rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('EditNarrative')">✕</button>
            <h3 class="font-bold text-center text-lg  p-5">Edit student narrative report</h3>
        </div>
        <div class="p-4">
            <form id="EditNarrativeReportsForm"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-auto h-[25rem]">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">First name</span>
                                </div>
                                <input type="text" required name="first_name" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Last name</span>
                                </div>
                                <input type="text" required name="last_name" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">School ID <span class="text-warning"> (Must be unique)</span></span>
                                </div>
                                <input type="number" min="0" required name="school_id" placeholder="XXXXXXXXX" oninput="this.value = this.value.slice(0, 9)" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Sex</span>
                                </div>
                                <div class="flex justify-start gap-2">
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Male</label>
                                        <input type="radio" name="stud_Sex" value="Male" class="radio bg-gray-300" checked />
                                    </div>
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Female</label>
                                        <input type="radio" name="stud_Sex" value="Female" class="radio bg-gray-300" />
                                    </div>
                                </div>
                            </label>
                        </div>
                        <input type="hidden" name="narrative_id" value="">
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select required name="program" class="select select-bordered w-full bg-slate-100 ">
                                    <option>Select program</option>
                                    <?php
                                    $sql = "SELECT * FROM  program";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option >'.$row['program_code'].'</option>
                                               ';

                                    }

                                    ?>
                                </select>

                            </label>

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Section</span>
                                </div>
                                <select required name="section" class="select select-bordered w-full bg-slate-100 ">
                                    <option>Select Section</option>
                                    <?php
                                    $sql = "SELECT * FROM  section";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option>'.$row['section'].'</option>';
                                    }
                                    ?>
                                </select>

                            </label>

                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT Adviser</span>
                                </div>
                                <select name="ojt_adviser" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select adviser</option>
                                    <?php
                                    $adv_option_query = "SELECT ui.*, acc.*
                                 FROM tbl_user_info ui
                                 INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
                                 WHERE ui.user_type IN ('admin', 'adviser') AND acc.status = 'active'";
                                    $result = $conn->query($adv_option_query);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['user_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No users found</option>";
                                    }
                                    ?>


                                </select>
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Replace Existing Narrative Report</span>
                                </div>
                                <input name="final_report_file" accept="application/pdf" type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                            </label>
                        </div>


                    </div>
                </div>
                <p id="loader_narrative_update" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>

                <div id="editNarrativeBtn" class="flex justify-center m-3 gap-2">
                    <button id="update_btn" class="btn btn-info btn-outline" >Update</button>
                    <button id="archive_btn" class="btn btn-error btn-outline" >Archive</button>
                </div>
            </form>
        </div>
    </div>
</dialog>

<dialog id="SuccessLUploadNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('SuccessLUploadNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-success absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>New narrative report has been uploaded!</span>
        </div>
    </div>
</dialog>
<dialog id="SuccessNarrativeEdit"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('SuccessNarrativeEdit')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-info absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Narrative report has been updated!</span>
        </div>
    </div>
</dialog>
<dialog id="archiveNarrativeNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('archiveNarrativeNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-warning absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Narrative report has been archived!</span>
        </div>
    </div>
</dialog>
