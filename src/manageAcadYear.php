<?php ?>


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
    <link rel="icon" type="image/x-icon" href="assets/insightlogo1.png">

    <title>View Student Reports</title>
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
                                <td>2021-2022</td>
                                <td>First</td>
                                <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                            </tr>
                            <tr>
                                <td>2021-2022</td>
                                <td>Second</td>
                                <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                            </tr>
                            <tr>
                                <td>2021-2022</td>
                                <td>Midyear</td>
                                <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                            </tr>
                            <tr>
                                <td>2022-2023</td>
                                <td>First</td>
                                <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                            </tr> <tr>
                                <td>2022-2023</td>
                                <td>Second</td>
                                <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                            </tr>
                            <tr>
                                <td>2022-2023</td>
                                <td>Midyear</td>
                                <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


</body>
<script src="js/Datatables.js"></script>
<script src="js/buttons_modal.js"></script>