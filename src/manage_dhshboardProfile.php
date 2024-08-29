

<form id="profileForm"   enctype="multipart/form-data">
    <div class="flex justify-center">
        <label for="profileImg" class=" rounded-3xl  cursor-pointer">
            <div class="avatar">
                <div class="w-40 rounded-full shadow-xl">
                    <img class="hover:opacity-50 transition-all" id="selectedProfile" src="assets/profile.jpg" />
                </div>
            </div>
            <input type="file" id="profileImg" name="profileImg" accept=".png, .jpg, .jpeg" hidden/>
        </label>
    </div>
    <div class="flex flex-col gap-2">
        <div class="flex justify-evenly gap-2">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">First name</span>
                </div>
                <input type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Middle name</span>
                </div>
                <input type="text" name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>

        </div>
        <div class="flex justify-evenly gap-2">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Last name</span>
                </div>
                <input type="text" name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Address</span>
                </div>
                <input type="text" name="user_address" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>

        </div>
        <div class="flex justify-evenly gap-2">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Contact number</span>
                </div>
                <input type="number" min="0" required name="contactNumber" placeholder="09XXXXXXXXX" oninput="this.value = this.value.slice(0, 11)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>

            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Sex</span>
                </div>
                <div class="flex justify-start gap-2">
                    <div class="flex justify-center items-center flex-col">
                        <label class="text-sm">Male</label>
                        <input type="radio" name="user_Sex" value="Male" class="radio bg-gray-300" />
                    </div>
                    <div class="flex justify-center items-center flex-col">
                        <label class="text-sm">Female</label>
                        <input type="radio" name="user_Sex" value="Female" class="radio bg-gray-300" />
                    </div>
                </div>
            </label>
        </div>
    </div>



    <p id="profileLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
    <div id="profilSbmt" class="flex justify-center m-3">
        <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
    </div>
</form>
<dialog id="prfupdateNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('prfupdateNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-success absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Profile has been updated</span>
        </div>
    </div>
</dialog>
