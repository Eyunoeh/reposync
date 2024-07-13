

<form id="profileForm"   enctype="multipart/form-data">
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
                    <span class="label-text text-slate-700">Last name</span>
                </div>
                <input type="text" name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
        </div>
        <div class="flex justify-evenly gap-2">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Address</span>
                </div>
                <input type="text" name="user_address" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Contact number</span>
                </div>
                <input type="number" min="0" required name="contactNumber" placeholder="09XXXXXXXXX" oninput="this.value = this.value.slice(0, 11)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
        </div>
        <div class="flex justify-evenly gap-2">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">School ID number <span class="text-warning"> (Must be unique)</span></span>
                </div>
                <input type="number" min="0" required name="school_id" placeholder="XXXXXXXX" oninput="this.value = this.value.slice(0, 9)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
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
        <div class="flex justify-evenly gap-2">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Password</span>
                </div>
                <input name="user_password"   type="password" placeholder="Enter password" data-theme="light"
                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text text-slate-700">Confirm password</span>
                </div>
                <input name="user_confPass"   type="password" placeholder="Enter password" data-theme="light"
                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
        </div>
    </div>



    <p id="new_adv_adminLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
    <div id="new_adv_adminBtn" class="flex justify-center m-3">
        <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
    </div>
</form>
