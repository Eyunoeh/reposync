<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
include '../DatabaseConn/databaseConn.php'
?>
<section class="  overflow-auto   bg-white pl-5 pb-5 pr-5 pt-0 h-full">
    <div class=" flex items-center sticky top-0 p-5 bg-white shadow rounded z-50 justify-between ">
        <div class="">
            <h1 class="font-bold text-2xl text-warning font-sans">Activities & Schedule</h1>
        </div>
        <div class="">
            <button class="btn btn-circle btn-success bg-opacity-70 " id="newAct" onclick="openModalForm('Act&shedModal') ;removeTrashButton()"><i class="fa-solid fa-plus"></i></button>
        </div>
    </div>
    <div class=" card-body grid place-items-center gap-5 p-3 sm:p-5 overflow-hidden sm:overflow-auto  scroll-smooth" id="actAndschedList">
        <div class="mt-5">
            <span class="loading loading-spinner loading-lg"></span>
        </div>

    </div>
</section>


<dialog id="Act&shedModal" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] h-full  flex flex-col text-slate-700">
        <div  class=" card-title sticky" id="act_schedtitle">
            <h1 class="font-bold text-center text-lg  p-2">Activity and Schedule Form</h1>
            <button class="absolute right-2 btn btn-sm btn-circle btn-ghost "  onclick="closeModalForm('Act&shedModal');removeTrashButton()">✕</button>
        </div>
        <div class="p-4 overflow-auto h-[90vh]">
            <form id="act_n_schedForm" class="" enctype="multipart/form-data">
                <input type="hidden" name="actionType" value="new" id="action_type">
                <input type="hidden" value="" name="announcementID" id="announcementID">
                <div class="flex flex-col gap-8  ">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-start gap-2">
                                <label class="form-control w-full ">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Title</span>
                                    </div>
                                    <input type="text" required name="Activitytitle" placeholder="Type here"
                                           class="  bg-slate-100 input input-bordered w-full " />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Announcement for:</span>
                                    </div>
                                    <select name="announcementTarget" required class="select select-bordered w-full bg-slate-100 " required>
                                        <option value="All" selected >All program</option>
                                        <?php
                                        $sql = "SELECT * FROM  program";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_assoc()){
                                            echo '<option value="'.$row['program_code'].'">'.$row['program_code'].'</option>
                                               ';

                                        }

                                        ?>
                                    </select>

                                </label>
                            </div>
                            <div class="flex justify-start gap-2">
                                <label class="form-control w-full ">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Text body</span>
                                    </div>
                                    <textarea  name="description" class="textarea textarea-success w-full" rows="5" cols="80" placeholder="Optional"></textarea>
                                </label>
                            </div>
                        </div>
                        <hr class=" m-3">
                        <h1 class="font-bold">Activity Dates</h1>
                        <div class="flex justify-center flex-wrap  gap-2 items-center ">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Starting date</span>
                                </div>
                                <input name="startDate" required  type="date"  data-theme="light"
                                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Ending date</span>
                                </div>
                                <input name="endDate"  required type="date"  data-theme="light"
                                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <hr class=" m-3">
                    </div>
                </div>
                <p id="SchedAndActLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                <div id="SchedAndActbtn" class="flex justify-center flex-col items-center gap-2">
                    <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
                    <label class="cursor-pointer label">

                        <input type="checkbox"  name="emailNotif" value="Notify" class="checkbox checkbox-xs mr-2 checkbox-info" />
                        <span class="label-text text-sm"> Notify users through Email?</span>
                    </label>
                </div>

            </form>
        </div>
    </div>
</dialog>

<dialog id="NewActSchedNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('NewActSchedNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-success absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>New activity has been posted students will get notified through email!</span>
        </div>
    </div>
</dialog>

<dialog id="UpdateaActSchedNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('UpdateaActSchedNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-info absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Activity has been updated!</span>
        </div>
    </div>
</dialog>
