<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>

<div class="px-9 pt-2 flex justify-end items-stretch flex-wrap  pb-0 bg-transparent">
    <button class="btn btn-neutral bg-slate-500 border-none text-slate-100" onclick="openModalForm('newAdvierDialog')">Create new</button>

</div>
<div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-start">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchbox" type="text" placeholder="Search" onkeyup="handleSearch('searchbox', 'AdvListTbl')">
                </div>
            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-96">
                <table id="AdvListTbl" class="w-full my-0 border-neutral-200" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                        <th class="p-3 text-start ">School ID</th>
                        <th class="p-3 text-start ">Name</th>


                        <th class="p-3 text-end ">Total advisory</th>
                        <th class="p-3 text-end ">Action</th>
                    </tr>
                    </thead>
                    <tbody id="advList" class="text-center text-slate-600">
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">210101223</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">42</span>
                        </td>

                        <td class="p-3 text-end">
                            <a onclick="openModalForm('editAdv_admin');" href="#" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">210101279</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">50</span>
                        </td>

                        <td class="p-3 text-end">
                            <a onclick="openModalForm('editAdv_admin')" href="#" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<dialog id="newAdvierDialog" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[40rem] max-h-[40rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky flex justify-end mr-2">
            <button class="btn btn-sm btn-circle btn-ghost " onclick="closeModalForm('newAdvierDialog')">✕</button>
        </div>
        <div class="p-4">
            <form id="admin_adv_Form"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-auto h-[27rem]">
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
                                        <input type="radio" name="user_Sex" value="Male" class="radio bg-gray-300" checked />
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
                                    <span class="label-text text-slate-700">Account email</span>
                                </div>
                                <input name="user_Email" type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Access Type</span>
                                </div>
                                <select name="user_type" class=" select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select role</option>
                                    <option value="adviser">OJT adviser</option>
                                    <option value="admin">Admin</option>
                                </select>
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
                </div>
                <p id="new_adv_adminLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                <div id="new_adv_adminBtn" class="flex justify-center m-3">
                    <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</dialog>
<dialog id="editAdv_admin" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[43rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky  flex justify-end">
            <button class="btn btn-sm btn-circle btn-ghost mt-2 mr-2" onclick="closeModalForm('editAdv_admin')">✕</button>
        </div>
        <div class="p-4">
            <form id="EditAdviserForm"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-y-auto h-[25rem]">
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
                                <input type="number" min="0" required name="school_id" placeholder="XXXXXXXXX" oninput="this.value = this.value.slice(0, 9)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Sex</span>
                                </div>
                                <div class="flex justify-start gap-2">
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Male</label>
                                        <input type="radio" name="user_Sex" value="Male" class="radio bg-gray-300" checked />
                                    </div>
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Female</label>
                                        <input type="radio" name="user_Sex" value="Female" class="radio bg-gray-300" />
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="flex justify-start">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Access type</span>
                                </div>
                                <select name="user_type" class=" select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Change role</option>
                                    <option value="adviser">OJT adviser</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </label>
                        </div>
                        <hr class="w-full border bg-slate-700 mt-10 ">
                        <div class="text-start  cursor-pointer" >
                            <div class="tooltip tooltip-right tooltip-error"
                                 data-tip="Both input fields are required to change the account information."><p class=" font-bold">Account Information <i class="fa-solid fa-circle-exclamation text-error"></i></p>
                            </div>
                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Account email</span>
                                </div>
                                <input name="user_Email" type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Password</span>
                                </div>
                                <input name="user_Pass" value="" minlength="8" type="password" placeholder="Change user password"
                                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <a class="transition-all text-error font-bold font-sans cursor-pointer text-end pr-6 m-3 hover:opacity-50 active:text-slate-500" onclick="openModalForm('deactivate_adv_acc')">Deactivate account? </a>
                    </div>
                </div>
                <input name="user_id" type="hidden" value="">

                <p id="editAdVLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>

                <div id="editStudBtn" class="flex justify-center m-3 gap-2.5 ">
                    <button id="update_stud_btn" class="btn btn-info btn-outline w-1/2" >Update</button>
                    <!--<button id="archive_stud_btn" class="btn btn-error btn-outline" >Archive</button>-->
                </div>
            </form>
        </div>
        <dialog id="deactivate_adv_acc" class="modal bg-black  bg-opacity-40 ">
            <div class="card bg-slate-50 w-[80vw] absolute top-10 sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
                <div  class=" card-title sticky ">
                    <h3 class="font-bold text-center text-lg  p-5">Are you sure you want to deactivate student account?</h3>
                </div>
                <div class="p-4 w-full flex justify-evenly">
                    <a id="deactivate_adv" class="btn  btn-error w-1/4 " onclick="closeModalForm('deactivate_adv_acc');deactivate_account(this.getAttribute('data-user_id'), 'editAdv_admin')" data-user_id="">Deactivate</a>
                    <button class="btn  btn-info  w-1/4 " onclick="closeModalForm('deactivate_adv_acc')">Close</button>
                </div>
            </div>
        </dialog>
    </div>
</dialog>

