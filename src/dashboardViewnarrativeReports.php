

<?php

session_start();

if (!isset($_SESSION['log_user_type'])){
    header('Location: 404.php');
}
include '../DatabaseConn/databaseConn.php';



$getPrograms = "SELECT * FROM program";
$getProgStmt = $conn->prepare($getPrograms);
$getProgStmt->execute();
$res = $getProgStmt->get_result();
$programCodes = [];

while ($row = $res->fetch_assoc()) {
    $programCodes[] = $row['program_code'];
}

/*if (!isset($_GET['program']) || !in_array($_GET['program'], $programCodes)) {
    header('Location: dashboard.php');
    exit();
}*/







?>


<!DOCTYPE html>
<html lang="en" data-theme="light">
<head data-theme="light">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">
    <link rel="icon" type="image/x-icon" href="assets/cvsulogo-removebg-preview.png">

    <title>Narrative Reports</title>
</head>


<body  class="min-h-screen bg-slate-200">
<div class="overflow-y-hidden h-[100vh] relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white">
    <div class="relative flex flex-col min-w-0 break-words rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent ">
            <a href="<?=$_SESSION['log_user_type'] == 'student'? 'index.php?page=narratives':'dashboard.php'?>" class="btn btn-outline font-bold text-slate-700">
                <?=$_SESSION['log_user_type'] == 'student'? '<i class="fa-solid fa-house"></i> Home':'<i class="fa-solid fa-circle-left"></i> Dashboard'?>
                </a>


            <form class="flex justify-start w-[40%]">

                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">

            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-[80vh]">
                <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200 text-sm" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                        <?php if ($_SESSION['log_user_type'] !== 'student'):?>

                        <th class="p-3 text-start ">School ID</th>
                        <?php endif;?>
                        <th class="p-3 text-start min-w-10">Name</th>
                        <th class="p-3 text-start min-w-10">OJT adviser</th>
                        <th class="p-3 text-start min-w-10">Semester</th>
                        <th class="p-3 text-start min-w-10">Academic year</th>



                        <th class="p-3 text-end ">Action</th>
                    </tr>
                    </thead>
                    <tbody id="narrativeReportsTableBody" class="text-center text-slate-600">
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start w-[10rem]">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">first_name lairst_</span>
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
</div>



<?php if ($_SESSION['log_user_type'] !== 'student'):?>
<dialog id="EditNarrative" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('EditNarrative')">✕</button>
            <h3 class="font-bold text-center text-lg  p-5">Edit student narrative report</h3>
            <div data-tip="Download PDF" class="tooltip tooltip-bottom ">
                <a id="dlLink" target="_blank" class="btn btn-circle hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2 text-info"><i class="fa-solid fa-download"></i></a>
            </div>
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
                                    <span class="label-text text-slate-700">Middle name</span>
                                </div>
                                <input type="text"  name="middle_name" placeholder="Optional" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
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
                                    <span class="label-text text-slate-700 text-center">Sex</span>
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
                                 WHERE ui.user_type IN ('admin', 'adviser') and ui.user_id = ?  AND acc.status = 'active'";

                                    $adv_option_querySTMT = $conn->prepare($adv_option_query);
                                    $adv_option_querySTMT->bind_param('i', $_SESSION['log_user_id']);
                                    $adv_option_querySTMT->execute();
                                    $result = $adv_option_querySTMT->get_result();
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
                                        echo '<option>'.$row['year'].''.$row['section'].'</option>';
                                    }
                                    ?>
                                </select>

                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">School Year</span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <input type="number" required name="startYear" oninput="this.value = this.value.slice(0, 4)" class="bg-slate-100 input input-bordered w-full max-w-xs" placeholder="0000" />
                                    <p class="text-center items-center font-bold text-lg"> - </p>
                                    <input type="number" required name="endYear" oninput="this.value = this.value.slice(0, 4)" class="bg-slate-100 input input-bordered w-full max-w-xs" placeholder="0000" />
                                </div>

                            </label>

                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Student Company / Institution</span>
                                </div>
                                <input type="text" required name="companyName" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Training Hours</span>
                                </div>
                                <input type="number" required name="trainingHours" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">

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



<?php endif;?>
<script>
    document.addEventListener('DOMContentLoaded', function (){
        dashboard_student_NarrativeReports();
    })
    async function dashboard_student_NarrativeReports() {
        let program =  new URLSearchParams(window.location.search).get('program');


        const  narratives  = await $.ajax({
            url: '../ajax.php?action=getPublishedNarrativeReport&program=' + program ,
            method: 'GET',
            dataType: 'json'
        });
        let narrative_listData = narratives.data
        let narratives_length = narrative_listData && Object.keys(narrative_listData).length
        let narrativeTblData = '';
        if (narratives_length < 0){

        }else {
            const  adviserList  = await $.ajax({
                url: '../ajax.php?action=getAdvisers' ,
                method: 'GET',
                dataType: 'json'
            });

            let advisers = adviserList.data.reduce((acc, adviser) => {
                let { user_id, first_name, last_name } = adviser;
                if (!acc[user_id]) {
                    acc[user_id] = { name: `${first_name} ${last_name}`, user_id: user_id };
                }
                return acc;
            }, {});


            console.log(advisers[45].name);

            Object.entries(narrative_listData).forEach(([key, narrative]) => {
                let years = narrative.ay_submitted.split(',');
                let startingAC = years[0].trim();
                let endingAC =  years[1].trim();
                let formattedSem = {
                    First: '1st',
                    Second: '2nd',
                    Summer: 'Summer'
                };
                narrativeTblData += `<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">${narrative.enrolled_stud_id}</span>
                        </td>
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${narrative.first_name} ${narrative.last_name}</span>
                        </td>
                         <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal  break-words">${advisers[narrative.adv_id].name}</span>
                        </td>
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${formattedSem[narrative.sem_submitted]}</span>
                        </td>
                         <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${startingAC} - ${endingAC}</span>
                        </td>

                        <td class="p-3 text-end ">
                           <a onclick="openModalForm(\'EditNarrative\');editNarrative(this.getAttribute(\'data-narrative\'))" id="archive_narrative" data-narrative="${narrative.narrative_id}" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="flipbook.php?view=${narrative.narrative_id}" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>`;


            });
            $('#narrativeReportsTableBody').html(narrativeTblData);

        }

    }
</script>
<script src="js/buttons_modal.js"></script>
<script src="js/Datatables.js"></script>

</body>