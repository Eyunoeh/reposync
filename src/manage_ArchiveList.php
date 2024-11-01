<?php session_start();
if (!isset($_SESSION['log_user_type']) or $_SESSION['log_user_type'] !== 'admin'){
    header('Location: index.php');
    exit();
}
if (!isset($_GET['route']) or !in_array($_GET['route'], ['NarrativeReports', 'Users'])){
    header('Location: dashboard.php');
    exit();
}
$documentTitle = "";
if ($_GET['route'] === 'NarrativeReports'){
    $documentTitle = 'Archived Narrative Reports';
}else{
    $documentTitle = 'Archived Users';
}

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
    <script src="js/Datatables.js" ></script>

    <title><?= $documentTitle ?></title>
</head>


<body  class="min-h-screen bg-slate-200">
<div class="overflow-y-hidden h-[100vh] relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white">
    <div class="relative flex flex-col min-w-0 break-words rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent ">

            <a href="dashboard.php" class="btn btn-outline font-bold text-slate-700"><i class="fa-solid fa-house"></i> Dashboard</a>

            <form class="flex justify-start w-[40%]">

                <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch(this.id, 'ArhiveTable')">
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-[80vh]">
                <table id="ArhiveTable" class="w-full my-0 border-neutral-200 text-sm" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" id="archiveThRow">


                    </tr>
                    </thead>
                    <tbody id="ArchiveTbody" class="text-center text-slate-600">
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start w-[10rem]">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">123123123</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">BSIT</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">OJT Adviser Name</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">2020 -2021</span>
                        </td>
                        <td class="p-3  flex gap-2 justify-end">
                            <a href="" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                            <a href="" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr class="w-full p-2">

    <dialog id="ArhiveModal" class="modal bg-black  bg-opacity-40">
        <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[38rem]  flex flex-col text-slate-700">
            <div  class=" card-title sticky ">
                <a class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('ArhiveModal')">✕</a>
                <?php  if($_GET['route'] === 'NarrativeReports'):?>
                <h3 class="font-bold text-center text-lg  p-5">Archived student narrative report</h3>
                    <div data-tip="Download PDF" class="tooltip tooltip-bottom">

                        <a id="dlLink" href="" target="_blank" class="btn btn-circle  hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2 text-info"><i class="fa-solid fa-download"></i></a>
                    </div>
                <?php  elseif($_GET['route'] === 'Users'):?>
                    <h3 class="font-bold text-center text-lg  p-5">Archived User</h3>

                <?php  endif;?>

            </div>


            <div class="p-4" id="ArhiveForm">


            </div>
            <p id="loader_narrative_update" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>

            <div id="editNarrativeBtn" class="flex justify-center m-3 gap-2">
                <a id="retrieve_btn" class="btn btn-warning btn-outline" >Retrieve</a>
            </div>
        </div>
    </dialog>
</div>
<div id="notifBox" onclick="resetAlertBox(this.id)">

</div>
<script >

    document.addEventListener('DOMContentLoaded', function (){
        const route = new URLSearchParams(window.location.search).get('route');
        let  endpoint;
        let tableHeadRow;
        if (route === 'NarrativeReports'){
            endpoint = 'getArchiveNarrative';

            tableHeadRow  = `<th class="p-3 text-start ">School ID</th>
                    <th class="p-3 text-start min-w-10">Name</th>
                    <th class="p-3 text-start min-w-10">Program</th>
                    <th class="p-3 text-start min-w-10">OJT adviser</th>
                    <th class="p-3 text-start min-w-10">Batch</th>
                    <th class="p-3 text-end ">Action</th>`;

        }else if (route === 'Users'){
            endpoint = 'getArchiveUsers';
            tableHeadRow = `<th class="p-3 text-start min-w-10">School ID</th>
                    <th class="p-3 text-start min-w-10">Name</th>
                    <th class="p-3 text-start min-w-10">User type</th>
                    <th class="p-3 text-start min-w-10">Email</th>
                    <th class="p-3 text-center ">Action</th>`;

        }
        $('#archiveThRow').html(tableHeadRow);
        $.ajax({
            url: '../ajax.php?action=' + encodeURIComponent(endpoint),
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.response === 1){
                    let tbRowData;
                    let  data = response.data;
                    if (route === 'NarrativeReports'){
                        let flipbookCodes = response.flipbookCode;

                        for (let i = 0; i < data.length ; i++){
                            let  schoolYear = data[i].sySubmitted.split(",");
                            let middleName = '';
                            if (data[i].middle_name !== 'N/A' ){
                                middleName = data[i].middle_name;
                            }

                            tbRowData += `<tr><td class="p-3 text-start w-[10rem]">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${data[i].stud_school_id}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].first_name} ${middleName} ${data[i].last_name}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].program}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].OJT_adviser_Fname} ${data[i].OJT_adviser_Lname}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${schoolYear.join(" - ")}</span>
                        </td>
                        <td class="p-3  flex gap-2 justify-end">
                            <a href="flipbook.php?view=${flipbookCodes[i]}" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                            <a onclick="openModalForm('ArhiveModal'); retrieveArchiveNarrativeReportInfo(${data[i].narrative_id})" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td> </tr>`;
                        }
                    }else if (route === 'Users'){
                        for (let i = 0; i < data.length ; i++){
                            let middleName = '';
                            if (data[i].middle_name !== 'N/A' ){
                                middleName = data[i].middle_name;
                            }
                            tbRowData += ` <tr>
                        <td class="p-3 text-start w-[10rem]">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${data[i].school_id}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].first_name} ${middleName} ${data[i].last_name}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].user_type}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].email}</span>
                        </td>
                        <td class="p-3  text-center">
                            <a onclick="openModalForm('ArhiveModal') ;retrieveArchiveUserInfo(${data[i].user_id})"
                            class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                        </tr>`;
                        }
                    }
                    $('#ArchiveTbody').html(tbRowData);
                    let retriveBTN = document.getElementById('retrieve_btn');

                    retriveBTN.addEventListener('click', function() {
                        let dataId = retriveBTN.getAttribute('data-id');
                        UnarchiveData(dataId, route);


                    });
                }else if (response.response === 0){
                    $('#ArchiveTbody').html('<tr><td colspan="9">No Result</td></tr>');
                }else{
                    console.log(response.message)
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    })
</script>
<script src="js/ArchiveList.js"></script>
<script src="js/buttons_modal.js"></script>
</body>
</html>
