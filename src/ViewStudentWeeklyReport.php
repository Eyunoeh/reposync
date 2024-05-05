<?php
session_start();
if (!isset($_SESSION['log_user_type']) || ($_SESSION['log_user_type'] !== 'admin' && $_SESSION['log_user_type'] !== 'adviser')) {
    header("Location: index.php");
    exit();
}

include "../DatabaseConn/databaseConn.php";
include '../encryptionFunction.php';
$secret_key = 'TheSecretKey#02';
decrypt_data($_GET['checkStudent'], $secret_key);
?>
<!DOCTYPE html>
<html lang="en">
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
    <title>Document</title>
</head>
<body  class="min-h-screen bg-slate-200">
<main class="max-w-6xl mx-auto grid place-items-center text-gray-700 overflow-auto" id="mainContent">
    <div class="w-full max-w-full ">
        <div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
            <div class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
                <!-- card header -->
                <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[75px] pb-0 bg-transparent">
                    <form class="flex w-[40%] justify-between hidden sm:inline-flex">
                        <div class="w-full">
                            <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
            focus:outline-none focus:shadow-outline" id="weeklyReportSearch" type="text" placeholder="Search"
                                   onkeyup="handleSearch('weeklyReportSearch','stud_weekly_report')">
                        </div>
                    </form>
                </div>
                <div class="px-9 flex justify-end w-full">
                </div>
                <div class="block py-8 pt-6 px-9">
                    <div id="table_card" class="overflow-auto h-[70vh] scroll-smooth">
                        <table class="w-full my-0  border-neutral-200 " id="stud_weekly_report" >
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
                                    <div class="tooltip tooltip-bottom" data-tip="Update remark">
                                        <a class="text-light-inverse text-md/normal mb-1 hover:cursor-pointer font-semibold
                                    transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"  onclick="openModalForm('resubmitReport')"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </div>
                                </td>
                            </tr>
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
                                    <div class="tooltip tooltip-bottom" data-tip="Update remark">
                                        <a class="text-light-inverse text-md/normal mb-1 hover:cursor-pointer font-semibold
                                    transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"  onclick="openModalForm('resubmitReport')"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </div>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<dialog id="comments" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] h-[100vh] lg:h-[37rem]  flex flex-col text-slate-700 overflow-auto">
        <div class="card-title sticky">
            <form method="dialog">
                <button class=" btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('comments')">✕</button>
            </form>
            <h3 class="font-bold text-lg text-center text-black  top-0  p-5">Report Comments</h3>
        </div>
        <div class="overflow-auto card-body">
            <div class="grid place-items-center">

                <p class="py-4 px-2 border bg-slate-200 rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea ex facilis magni modi, molestias perferendis placeat quam quasi, qui ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                <div class="flex flex-wrap gap-1">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                </div>

            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-sm text-black text-center">2:30pm</p>
                <p class="text-sm text-black text-center">4/16/2024</p>
            </div>
            <div class="grid place-items-center">
                <p class="py-4 px-2 border rounded-lg bg-slate-200 min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea ex facilis magni modi, molestias perferendis placeat quam quasi, qui ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                <div class="flex flex-wrap gap-1">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[8rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-sm text-black text-center">4:30pm</p>
                <p class="text-sm text-black text-center">4/16/2024</p>
            </div>
        </div>
        <div class="m-3 shadow-2xl rounded bg-gray-300 bg-opacity-70 ">
            <form class="flex sm:flex-row flex-col justify-evenly items-center p-2 flex-wrap sm:gap-2 gap-0">
                <textarea name="revision_comment" class=" sm:w-auto w-full flex-grow textarea  max-h-24 textarea-bordered  bg-slate-100" placeholder="Type here"></textarea>
                <div class=" flex  justify-center sm:justify-evenly sm:w-auto w-full flex-grow items-center p-2 sm:p-0">
                    <label class="form-control max-w-xs">
                        <div class="label">
                            <span class="label-text text-slate-700">Image attachment</span>
                        </div>
                        <input name="final_report_file" accept="image/png, image/jpeg" multiple  type="file"
                               class="block  text-sm text-black file:mr-4
                    file:py-2 file:px-4 file:rounded-full file:border-0
                    file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                    </label>
                    <button class="btn btn-accent">Send <i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>
    <dialog id="img_modal" class="modal bg-black  bg-opacity-40">
        <div class="card  w-[100vw] sm:w-[55rem] h-[100vh]
          flex flex-col text-slate-700 overflow-auto">
            <div class="card-title sticky">
                <form method="dialog">
                    <button class=" btn btn-sm btn-circle absolute right-2 top-2" onclick="closeModalForm('img_modal')">✕</button>
                </form>
            </div>
            <div class="card-body  overflow-auto">
                <img src="NarrativeReportsPDF/backcover.PNG" class="object-contain">

            </div>
        </div>
    </dialog>
</dialog>
<script src="js/buttons_modal.js"></script>
</body>


