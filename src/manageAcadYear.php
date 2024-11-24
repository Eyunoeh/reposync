<?php
session_start();
if (!isset($_SESSION['log_user_type']) || $_SESSION['log_user_type'] !== 'admin') {
    header('Location: dashboard.php');
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

    <title>Manage academic year</title>
</head>
<body  class="min-h-screen bg-slate-200">
<main class="max-w-6xl mx-auto grid place-items-center text-gray-700 overflow-auto" id="mainContent">
    <div class="w-full max-w-full ">
        <div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
            <div class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
                <!-- card header -->
                <div class="px-9 pt-5 flex justify-between
                 items-stretch flex-wrap min-h-[75px] pb-0 bg-transparent">
                    <a href="dashboard.php" class="btn btn-sm btn-outline font-bold text-slate-700"><i class="fa-solid fa-circle-left"></i> Dashboard</a>

                    <div class="w-[40%]">
                        <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
            focus:outline-none focus:shadow-outline" id="AYSearch" type="text" placeholder="Search"
                               onkeyup="handleSearch('AYSearch','AcadYearsTbl')">
                    </div>
                </div>
                <div class="px-9 flex justify-between flex-wrap-reverse pb-0 bg-transparent items-end">
                    <label class="form-control w-full max-w-sm">
                        <div class="label">
                            <span class="label-text text-slate-700">Current Semester & Academic Year</span>
                        </div>
                        <div class="flex gap-2 justify-start items-center w-full">
                            <select id="CurracademicYear" required name="CurracademicYear" class="disabled:text-black bg-slate-100 select select-bordered w-full max-w-sm">
                                <option disabled selected>Select</option>
                                <option value="">2021-2022</option>
                                <option value="200">2022-2023</option>
                                <option value="240">2023-2024</option>
                            </select>
                            <button class="btn btn-ghost btn-sm">Save</button>
                        </div>
                    </label>

                    <button class="btn btn-success self-start" onclick="openModalForm('ManageAcadYear'); ">Create Academic Year</button>
                </div>


                <div class="px-9 flex justify-end w-full">
                </div>
                <div class="block py-8 pt-6 px-9">
                    <div id="table_card" class="overflow-y-auto overflow-x-hidden h-[70vh] scroll-smooth">
                        <table class="table  bordered" id="AcadYearsTbl">
                            <!-- head -->
                            <thead class="w-full sticky top-0 shadow bg-slate-100 rounded text-slate-700">
                            <tr>
                                <th onclick="sortTable(0,'AcadYearsTbl')" class="cursor-pointer">Academic Year<span class="sort-icon text-xs"></span></th>
                                <th onclick="sortTable(1,'AcadYearsTbl')" class="cursor-pointer " >Semester<span class="sort-icon text-xs"></span></th>
                                <th class="text-center">Action</th>

                            </tr>
                            </thead>
                            <tbody id="AcadYears">

                            <tr>
                                <td>2022-2023</td>
                                <td>Midyear</td>
                                <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                            </tr>
                            </tbody>
                        </table>
                        <div id="noAcadYearsNote" class="flex justify-center items-center ">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<dialog id="ManageAcadYear" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem]   flex flex-col text-slate-700">
        <div  class=" card-title sticky flex justify-end" id="act_schedtitle">
            <button class=" btn btn-sm btn-circle btn-ghost "  onclick="closeModalForm('ManageAcadYear');manageAcadYearReset()">✕</button>
        </div>
        <div class="p-4 ">
            <form id="ManageAcadYearForm" class="overflow-y-auto h-full max-h-[87vh]" >
                <input type="hidden" name="action_type" value="new" id="action_type">
                <input type="hidden" id="ay_ID" name="ay_ID" value="">
                <div class="w-full flex justify-end" id="option">

                </div>
                <div class="flex flex-col gap-8  " id="SectionProgramFormInputs">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-2">

                            <div class=" flex justify-evenly gap-2 w-full items-end">
                                <div class=" w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Academic Year</span>
                                    </div>
                                    <div class="flex gap-2 justify-start items-center w-full">
                                        <input  type="number" oninput="this.value = this.value.slice(0, 4)" required name="aystartYear" id="aystartYear" placeholder="XXXX"  class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs"  />
                                        <p class="text-center items-center "> to </p>
                                        <input  type="number" oninput="this.value = this.value.slice(0, 4)" required name="ayendYear" id="ayendYear" placeholder="XXXX" class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs"  />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Semester</span>
                                    </div>
                                    <select id="semester" required name="semester" class="disabled:text-black bg-slate-100 select select-bordered w-full max-w-xs" >
                                        <option disabled selected>Select</option>
                                        <option value="First">First</option>
                                        <option value="Second">Second</option>
                                        <option value="Midyear">Midyear</option>
                                    </select>
                                </div>
                            </div>

                            <div class=" flex justify-between gap-2 w-full items-end">

                                <div class=" w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Program code</span>
                                    </div>
                                    <select id="program" onchange="render_CourseOptions(this.value)"  name="program" class="disabled:text-black bg-slate-100 select select-bordered w-full max-w-xs" >
                                        <option disabled selected>Select</option>


                                    </select>
                                </div>
                                <div class=" w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Course code</span>
                                    </div>
                                    <select id="program_course"  name="program_course" class="disabled:text-black bg-slate-100 select select-bordered w-full max-w-xs" >
                                        <option disabled selected>Select program first</option>

                                    </select>
                                </div>
                                <div class=" w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Yr & Sec</span>
                                    </div>
                                    <a onclick="openModalForm('sectionOptions')" class="w-full btn-neutral btn-outline btn">Select</a>
                                </div>
                                <a class="btn btn-success " id="addCourseBtn">Add</a>
                                <input type="hidden" name="availableCourse" id="availableCourse">
                            </div>

                        </div>
                    </div>
                </div>
                <dialog id="sectionOptions" class="modal bg-black  bg-opacity-40">
                    <div class="card bg-slate-50  w-[25rem]   flex flex-col text-slate-700">
                        <div  class=" card-title sticky flex justify-end" id="">
                            <a class=" btn btn-sm btn-circle btn-ghost "  onclick="closeModalForm('sectionOptions')">✕</a>
                        </div>
                        <div class="p-4 overflow-auto max-h-[80vh]" id="yrSecOptions">
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <span class="label-text">1A</span>
                                    <input type="checkbox" value="1A" class="checkbox dynamic-checkbox" />
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <span class="label-text">2A</span>
                                    <input type="checkbox" value="2A" class="checkbox dynamic-checkbox" />
                                </label>
                            </div>
                        </div>
                    </div>

                </dialog>

                <hr class="mt-3">
                <div class="flex justify-start p-3">

                        <span class="font-bold">
                         Available program course
                        </span>
                </div>
                <div class="w-full my-3" id="progCourseSec">
                    <table class="table table-sm bordered" id="courseTbl">
                        <!-- head -->
                        <thead class="w-full sticky top-0 shadow bg-slate-100 rounded text-slate-700">
                        <tr>
                            <th  class="cursor-pointer">Program code</th>
                            <th  class="cursor-pointer">Course</th>
                            <th  class="cursor-pointer">Yr & Sec</th>
                            <th class="">Action</th>

                        </tr>
                        </thead>
                        <tbody id="ay_openProgCourse" class="text-start">


                        </tbody>
                    </table>
                    <div id="nocourseNote" class="flex justify-center items-center ">
                        <p class="text-sm text-slate-700 font-sans">No selected program course</p>
                    </div>
                </div>
                <hr class=" m-3">
                <input type="hidden" name="Ay_availableCourse" id="Ay_availableCourse">
                <p id="progyrsecLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                <div id="progyrsecLoaderbtn" class="flex justify-center ">
                    <button id="progYrSecSubmit" class="btn btn-success btn-outline w-1/4" >Add</button>
                </div>
            </form>

        </div>
        <div id="errNotifcotainer" onclick="resetAlertBox(this.id)"></div>
    </div>
</dialog>

<div id="notif" onclick="resetAlertBox(this.id)"></div>


</body>
<script src="js/Datatables.js"></script>
<script src="js/buttons_modal.js"></script>
<script src="js/manageAcadyear.js"></script>
