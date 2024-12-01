<?php
include '../vendor/autoload.php';
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);




include "../DatabaseConn/databaseConn.php";
include "../PhpMailer_producer.php";
include '../functions.php';
session_start();


if (!isset($_SESSION['log_user_type']) || ($_SESSION['log_user_type'] !== 'admin' && $_SESSION['log_user_type'] !== 'adviser')) {
    header("Location: index.php");
    exit();
}
if (!isset($_GET['checkStudent'])) {
    header("Location: 404.php");
    exit();
}
if (!isset($_GET['weeklyJournal'])) {
    header("Location: 404.php");
    exit();
}
$secret_key = 'TheSecretKey#02';
$student_user_id = decrypt_data($_GET['checkStudent'], $secret_key);
$file_id_id = decrypt_data($_GET['weeklyJournal'], $secret_key);

if (!$student_user_id){
    header("Location: dashboard.php");
}
if (!$file_id_id){
    header("Location: StudentWeeklyReport.php");
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

    <title>View Student Reports</title>
</head>
<body  class="min-h-screen bg-slate-700 overflow-x-hidden overflow-y-auto">

<div class="flex justify-between items-center gap-2 sticky pb-2 top-2 px-5 z-10">
    <a href="StudentWeeklyReport.php?checkStudent=<?=urlencode(encrypt_data($student_user_id, $secret_key));?>" class=" opacity-50 hover:opacity-100 transition-all cursor-pointer btn  btn-neutral  z-20 top-5 left-5   font-bold text-white ">
        <i class="fa-solid fa-circle-left"></i> Back
    </a>
    <div class="opacity-50 hover:opacity-100 transition-all form-control w-full  max-w-xs  p-2 bg-slate-200 rounded-lg shadow-lg ">
        <div class="label">
            <span class="label-text">Status</span>
        </div>
        <div class="flex justify-center gap-2 items-end w-full">
            <select id="report_Stat" name="report_Stat" class="flex-grow select select-bordered">
                <option selected value="pending">Pending</option>
                <option value="revision">With revision</option>
                <option value="approved">Approve</option>
            </select>
            <button class="btn btn-md btn-circle btn-info cursor-pointer">Save</button>
        </div>
    </div>
</div>





<div class="flex h-screen gap-2 sm:flex-row flex-col">

    <!-- PDF View Section -->
    <div class="relative flex flex-col sm:w-3/5 w-screen  border shadow-lg bg-white">
        <iframe src="assets/Gallarde_Jasmin-BSIT.pdf" class="flex-grow w-screen sm:w-full sm:h-0 h-[100vh]" frameborder="0"></iframe>
        <div class="absolute top-0 right-0 h-full w-2 bg-gray-300 cursor-ew-resize"></div>
    </div>


    <!-- Sidebar Section -->
    <div class="w-full  sm:w-3/5 flex-grow p-4 flex flex-col rounded-lg bg-white">
<!--        <div class="flex items-center justify-center p-4 ">

            <span class="text-lg font-semibold">Comment box</span>
        </div>-->
        <div id="comment_body" class="card bg-slate-50
        hover:cursor-pointer transition-all w-full flex-grow h-screen  card-body flex flex-col text-slate-700 overflow-auto">
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
            </div>
        </div>


        <div class="m-3 shadow-2xl rounded border bg-opacity-70 ">
            <div  id="chatBox" class="flex   justify-start items-center p-2  gap-2 ">
                <textarea name="revision_comment" class="sm:w-auto w-full flex-grow textarea  max-h-24 textarea-bordered  bg-slate-100" placeholder="Type here"></textarea>
                <input type="hidden" name="file_id" value="">

                <div class="relative">
                    <label title="Click to upload" for="button2"
                           class="cursor-pointer flex items-center
                               gap-4 px-6 py-4 before:border-gray-400/60
                                hover:before:border-gray-300 group before:bg-gray-100
                                 before:absolute before:inset-0 before:rounded-3xl before:border
                                  before:border-dashed before:transition-transform before:duration-300
                                  hover:before:scale-105 active:duration-75 active:before:scale-95">
                        <div class="w-max relative">
                            <img class="w-5" src="assets/clip-svgrepo-com.svg" alt="file upload icon" width="512" height="512">
                        </div>

                    </label>
                    <input hidden="" type="file" name="button2" id="button2">
                </div>
                <button class="btn btn-md btn-circle btn-ghost"><i class="fa-regular fa-paper-plane"></i></button>

            </div>
        </div>



    </div>

    </div>


</body>
<script>
    window.onload = function() {
        window.scrollTo(0, document.body.scrollHeight);
    };
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const resizer = document.querySelector('.cursor-ew-resize');
        const container = resizer.parentElement;

        // Restore the saved width on page load
        const savedWidth = localStorage.getItem('resizableContainerWidth');
        if (savedWidth) {
            container.style.width = savedWidth;
        }

        let isResizing = false;

        resizer.addEventListener('mousedown', (e) => {
            isResizing = true;
            document.body.style.cursor = 'ew-resize';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isResizing) return;

            // Calculate new width based on mouse position
            const newWidth = e.clientX - container.getBoundingClientRect().left;

            // Prevent the width from going too small
            if (newWidth > 200) { // Minimum width in pixels
                container.style.width = `${newWidth}px`;

                // Save the new width to localStorage
                localStorage.setItem('resizableContainerWidth', `${newWidth}px`);
            }
        });

        document.addEventListener('mouseup', () => {
            isResizing = false;
            document.body.style.cursor = '';
        });
    });
</script>



