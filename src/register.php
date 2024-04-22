<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <title>Document</title>
</head>
<body  class="bg-slate-100 grid place-items-center">
<main class="h-screen mx-auto flex  justify-center items-center sm:flex-row overflow-y-auto ">
    <img id="reg_img" class="hover:cursor-pointer lg:block hidden object-fit h-[32rem] w-full sm:w-auto md:w-[720px] border border-black " src="assets/reposync%20signup%20image-01.jpg" alt="Cookie Image">
    <div class="bg-white card h-[90vh] w-full  sm:w-96 sm:h-[32rem] bg-transparent text-neutral-content border-none md:border rounded-none">
        <div class="card-body flex flex-col justify-evenly  items-center lg:border-black lg:border ">
            <div class="text-center">
                <h2 class="text-2xl text-black font-bold">Sign Up</h2>
            </div>
            <section class="overflow-y-auto max-h-[20rem] scroll-smooth ">
                <form method="post" id="signup-form">
                    <div class="flex flex-col gap-2 justify-center text-black">
                        <label class="font-bold text-sm">Select Role</label>
                        <select class="w-full h-8 rounded bg-slate-50" name="user_role">
                            <option value="Student">Student</option>
                            <option value="Adviser">Adviser</option>
                        </select>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">First Name</label>
                            <input required name="first_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Enter your first name">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Last Name</label>
                            <input required name="last_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Enter your last name">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Email</label>
                            <input required name="email" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="email" placeholder="yourmail@example.com">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Password</label>
                            <input required name="password" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="password" placeholder="Enter your password">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Confirm Password</label>
                            <input required name="conf_password" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="password" placeholder="Confirm your password">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">School ID</label>
                            <input required name="sch_id" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="0000-00000">
                        </div>
                        <div id="program" class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Select Program</label>
                            <select required name="program" class="w-full h-8 rounded bg-slate-50">
                                <option>BSIT</option>
                                <option>BSBM</option>
                                <option>BSCpE</option>
                                <option>BSCS</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Upload ID</label>
                            <input name="file_sch_id" required type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                        </div>
                        <div id="reg_form" class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Registration Form</label>
                            <input name="reg_form" required type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                        </div>
                    </div>
                </form>
            </section>
            <div class="card-actions flex justify-center mt-4">
                <button id="sign-up-submit-btn" class="btn btn-success btn-outline mr-2 h-10 p-3 w-20">Submit</button>
            </div>
        </div>
    </div>
</main>
<div id="signup_modal" class="hidden absolute h-[100vh] w-full grid place-items-center bg-black bg-opacity-35">
    <div class=" card w-96 bg-slate-100  border-black">
        <div class="card-body items-center text-center">
            <h2 class="card-title text-slate-600">Check your email</h2>

            <p> Wait for the admin approval</p>
            <div class="mt-4 card-actions justify-end">
                <a href="index.php" class="btn btn-success btn-outline">Okay</a>
            </div>
        </div>
    </div>
</div>
<div id="loader" class="hidden absolute h-[100vh] w-full grid place-items-center bg-black bg-opacity-35">
    <span class="loading loading-dots loading-lg text-white"></span>
</div>



<script src="js/register.js"></script>
</body>
</html>