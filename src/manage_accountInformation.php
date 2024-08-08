
<?php session_start()?>
<section class="w-full flex  justify-center items-center h-full">
    <form id="Accountform" class="w-full"   enctype="multipart/form-data">
        <div class="flex justify-center m-5">
            <label class=" w-full max-w-lg flex-col flex items-center">

                <input type="email" name="user_Email" value="<?=$_SESSION['log_user_email']?>"  placeholder="Email" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
        </div>
        <div class="flex justify-center m-5">

            <label class=" w-full max-w-lg flex-col flex items-center">
                <input name="user_password"   type="password" autocomplete="off" placeholder="New password" data-theme="light"
                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>
        </div>
        <div class="flex justify-center m-5">

            <label class="w-full flex-col flex items-center max-w-lg">
                <input name="user_confPass"   type="password" autocomplete="off" placeholder="Confirm password" data-theme="light"
                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
            </label>



        </div>
        <p id="accountLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
        <div id="acccountSbmt" class="flex justify-center m-3">
            <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
        </div>
    </form>

</section>






<dialog id="accupdateNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('accupdateNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-success absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Acccount Information has been updated</span>
        </div>
    </div>
</dialog>
