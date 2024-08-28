<section class="w-full min-h-screen flex justify-center  mt-2">

    <div class="w-full max-w-7xl mx-auto p-5 rounded-lg shadow-lg bg-white min-h-[70vh]">
        <div class="flex justify-start">
            <a onclick="changeProfileSettingForm()" class="h font-semibold btn btn-ghost" ><i class="fa-solid fa-right-left"></i>
                <span id="accountSettingbtN">Profile Information</span>
            </a>
        </div>
        <form id="StudprofileForm" class=""   enctype="multipart/form-data">
            <div class="flex justify-center">
                <label for="profileImg" class=" rounded-3xl  cursor-pointer">
                    <div class="avatar">
                        <div class="w-40 rounded-full shadow-xl">
                            <img class="hover:opacity-50 transition-all" id="selectedProfile" src="assets/profile.jpg" />
                        </div>
                    </div>
                    <input type="file" id="profileImg" name="profileImg" accept="png, jpg" hidden/>
                </label>
            </div>
            <hr class="m-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[90%] overflow-auto">
                <div class="">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">First name</span>
                        </div>
                        <input type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Middle name</span>
                        </div>
                        <input type="text" name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Last name</span>
                        </div>
                        <input type="text" name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
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
                        <input type="number" name="contactNumber" placeholder="+63XXXXXXXXX"   oninput="this.value = this.value.slice(0, 11);" class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Sex</span>
                        </div>
                        <select name="stud_Sex" class="select select-bordered bg-slate-100 w-full">
                            <option disabled selected>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Company Name</span>
                        </div>
                        <input type="text" name="stud_compName" placeholder="Type here"   class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700">Training hours</span>
                        </div>
                        <input type="text" name="stud_TrainingHours" placeholder="Type here"    class=" bg-slate-100 input input-bordered w-full " />
                    </label>
                </div>
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
                        <input type="text" name="user_Email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
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
<dialog id="prfupdateNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('prfupdateNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-success absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Profile has been updated</span>
        </div>
    </div>
</dialog>
