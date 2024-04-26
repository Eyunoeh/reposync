<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
<body  class="min-h-screen bg-slate-200 ">

<main class="flex h-[100vh] ">
    <div class="sidebar hidden sm:inline-block scroll-smooth  p-2 w-[300px] min-w-[300px] overflow-y-auto  text-center bg-green-700" >
        <div class=" text-gray-100 text-xl bg-green-700 transition-all ">
            <div class="p-2.5 mt-1 flex items-center">
                <div class="avatar">
                    <div class="w-8 rounded">
                        <img src="assets/cvsulogo-removebg-preview.png" />
                    </div>
                </div>
                <h1 class="font-bold text-gray-200 text-[15px] ml-3">Reposync</h1>
                <i class="bi bi-x cursor-pointer ml-28 lg:hidden" onclick="//openSidebar()"></i>
            </div>
            <div class="my-2 bg-gray-600 h-[1px]"></div>
        </div>

        <div class=" p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 active:bg-slate-200 text-white">
            <i class="fa-solid fa-house"></i>
            <span class="text-[15px] ml-4  font-bold">Home</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-gauge"></i>
            <span class="text-[15px] ml-4  font-bold">Dashboard</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-solid fa-book"></i>
            <span class="text-[15px] ml-4  font-bold">Narrative Reports</span>
        </div>


        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-slate-200 hover:text-slate-700  text-white" onclick="dropdown()">
            <i class="fa-solid fa-bullhorn"></i>
            <div class="flex justify-between w-full items-center">
                <span class="text-[15px] ml-4  font-bold">Announcement</span>
            </div>
        </div>
        <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold " id="submenu">
            <h1 class="cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                <i class="fa-regular fa-note-sticky"></i>
                 Adviser Notes
            </h1>
            <h1 class="cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                <i class="fa-regular fa-calendar-days"></i>
                 Activities & Schedule
            </h1>
        </div>

        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-slate-200 hover:text-slate-700 text-white">
            <i class="fa-brands fa-font-awesome"></i>
            <span class="text-[15px] ml-4  font-bold">Students Weekly Report</span>
        </div>
        <div class="my-4 bg-gray-600 h-[1px]"></div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-slate-200 hover:text-slate-700  text-white" onclick="dropdown()">
            <i class="fa-solid fa-user"></i>
            <div class="flex justify-between w-full items-center">
                <span class="text-[15px] ml-4  font-bold">Users</span>
            </div>
        </div>
        <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold " id="submenu">
            <h1 class="cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">

                Student
            </h1>
            <h1 class="cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                Advisers
            </h1>
            <h1 class="cursor-pointer p-2 hover:bg-slate-200 hover:text-slate-700  text-white rounded-md mt-1">
                Admin
            </h1>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-error hover:text-slate-700  text-white">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="text-[15px] ml-4  font-bold">Logout</span>
        </div>
    </div>
    <div class="flex gap-2 flex-col  max-w-6xl overflow-auto">
        <div class="flex gap-2 ml-3 mt-3 flex-wrap xl:justify-center justify-evenly lg:justify-start h-full items-center text-slate-700 ">
            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32   bg-info text-center grid place-items-center shadow-xl">
                <h1 class="font-bold font-sans text-[4rem]">60</h1>
                <h1 class="font-sans text-[25px]">Active Students</h1>
            </div>
            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32  bg-info text-center grid place-items-center shadow-xl">
                <h1 class="font-bold font-sans text-[4rem]">13</h1>
                <h1 class="font-sans text-[25px]">Active Adviser</h1>
            </div>
            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32   bg-warning text-center grid place-items-center shadow-xl">
                <h1 class="font-bold font-sans text-[4rem]">23</h1>
                <h1 class="font-sans text-[25px]">Archive Student</h1>
            </div>
            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32  bg-error text-center grid place-items-center shadow-xl">
                <h1 class="font-bold font-sans text-[4rem]">60</h1>
                <h1 class="font-sans text-[25px]">Archive Adviser</h1>
            </div>

            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32   bg-success text-center grid place-items-center shadow-">
                <h1 class="font-bold font-sans text-[4rem]">300</h1>
                <h1 class="font-sans text-[25px]">Narrative Reports</h1>
            </div>
            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32  bg-accent text-center grid place-items-center shadow-xl">
                <h1 class="font-bold font-sans text-[4rem]">60</h1>
                <h1 class="font-sans text-[25px]">Weekly Report</h1>
            </div>

            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32  bg-purple-400 text-center grid place-items-center shadow-xl">
                <h1 class="font-bold font-sans text-[4rem]">60</h1>
                <h1 class="font-sans text-[20px]">Archive Weekly Report</h1>
            </div>
            <div class="card rounded h-60 w-full sm:w-[15rem] min-w-32 bg-secondary text-center grid place-items-center shadow-xl">
                <h1 class="font-bold font-sans text-[4rem]">60</h1>
                <h1 class="font-sans text-[20px]">Archive Narrative Report</h1>
            </div>
        </div>
    </div>
</main>
</body>