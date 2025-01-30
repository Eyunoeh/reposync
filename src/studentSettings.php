<?php
session_start();
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['log_user_type']) || $_SESSION['log_user_type'] !== 'student') {
    return;
}
?>

<section  class="w-full min-h-screen flex justify-center  mt-2">

    <div class="w-full max-w-7xl mx-auto p-5 rounded-lg shadow-lg bg-white min-h-[70vh]">
        <div class="flex justify-start">
            <a onclick="changeProfileSettingForm()" class="btn-ghost  font-semibold btn  text-info underline" >
                <i class="fa-solid fa-right-left"></i>
                <span id="accountSettingbtN">Profile Information</span>
            </a>
        </div>
        <form id="StudprofileForm" class=""   enctype="multipart/form-data" >
            <div class="flex justify-center">
                <label for="profileImg" class=" rounded-3xl  cursor-pointer">
                    <div class="avatar">
                        <div class="w-40 rounded-full shadow-xl">
                            <img class="hover:opacity-50 transition-all" id="selectedProfile"  />
                        </div>
                    </div>
                    <input type="file" id="profileImg" name="profileImg" accept=".png, .jpg, .jpeg" hidden/>
                </label>
            </div>
            <hr class="m-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[90%] overflow-auto">
                <div class="">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">First name</span>
                        </div>
                        <input oninput="validateInput(this)" type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Middle name</span>
                        </div>
                        <input oninput="validateInput(this)" type="text" name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Last name</span>
                        </div>
                        <input oninput="validateInput(this)" type="text" name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Address</span>
                        </div>
                        <input type="text" name="user_address" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Contact Number</span>
                        </div>
                        <input type="number" name="contactNumber" placeholder="+63×××××××××"   oninput="this.value = this.value.slice(0, 11);" class=" bg-slate-100 input input-bordered w-full [appearance:textfield] [&::-webkit-inner-spin-button]:hidden [&::-webkit-outer-spin-button]:hidden" />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Sex</span>
                        </div>
                        <select name="user_Sex" class="select select-bordered bg-slate-100 w-full">
                            <option disabled selected>Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">OJT Center</span>
                        </div>
                        <input type="text" name="stud_OJT_center" placeholder="Type here"   class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">OJT Contact</span>
                        </div>
                        <input type="text" name="stud_ojtContact" placeholder="Type here"    class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">OJT started</span>
                        </div>
                        <input type="date" name="OJT_started" placeholder="Type here"    class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">OJT ended</span>
                        </div>
                        <input type="date" name="OJT_ended" placeholder="Type here"    class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
            </div>
            <input type="hidden" name="studInfo" >


            <div id="" class=" mt-2 text-xs text-justify flex justify-center items-center">
                <p class="max-w-lg font-sans">
                    <span class="font-bold">Note: </span> The Cavite State University Carmona campus collects basic student information,
                    including full name, student ID, email address,
                    and contact details,
                    along with the OJT center , OJT contact, OJT started and OJT ended.
                    Only authorized school representatives will access this data for academic purposes,
                    ensuring confidentiality and compliance with data privacy policies.
                </p>


            </div>
            <p id="profielSubmitLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
            <div id="profielSubmitbtnContainer" class="flex justify-center m-5">
                <button id="profielSubmitbtn" class="btn btn-success btn-outline w-1/4" >Save</button>
            </div>
        </form>
        <form id="StudAccountInfo" class="hidden">
            <div class="">
                <div class="flex justify-center">
                    <label class="form-control max-w-lg w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Email</span>
                        </div>
                        <input type="text" value="<?=$_SESSION['log_user_email']?>" name="user_Email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div class="flex justify-center">
                    <label class="form-control max-w-lg w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">New password</span>
                        </div>
                        <input type="password"  autocomplete="off" name="user_password" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div class="flex justify-center">
                    <label class="form-control max-w-lg w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Confirm password</span>
                        </div>
                        <input type="password" autocomplete="off" name="user_confPass" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>

            </div>



            <p id="accInfoSubmitLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
            <div id="accInfoSubmitContainer" class="flex justify-center m-5">
                <button id="accInfoSubmitBtn" class="btn btn-success btn-outline w-1/4" >Save</button>
            </div>
        </form>


    </div>
</section>

