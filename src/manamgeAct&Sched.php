<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>
<section class="  overflow-auto   bg-white pl-5 pb-5 pr-5 pt-0 h-full">
    <div class=" flex items-center sticky top-0 p-5 bg-white shadow rounded z-50 justify-between ">
        <div class="">
            <h1 class="font-bold text-2xl text-warning font-sans">Activities & Schedule</h1>
        </div>
        <div class="">
            <button class="btn btn-circle btn-success bg-opacity-70 " onclick="openModalForm('Act&shedModal')"><i class="fa-solid fa-plus"></i></button>
        </div>
    </div>
    <div class=" card-body grid place-items-center gap-5 p-3 sm:p-5 overflow-hidden sm:overflow-auto  scroll-smooth">
        <div class="flex transform w-[50rem]  transition duration-500 shadow rounded
            hover:scale-110 hover:bg-slate-300  justify-start items-center cursor-pointer">
            <div class=" min-w-[12rem]  p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                <h4>June 22, 2024</h4>
                <h4>July 23, 2024</h4>
            </div>
            <div class="flex flex-col justify-center max-h-[10rem] overflow-auto p-3">
                <h1 class="font-semibold">Beginning of OJT</h1>
                <div class=" max-h-[10rem] overflow-auto">
                    <p class="text-justify text-sm pr-5">asdasdasdasdasd
                        assuLorem rsum dm dolor sit nse
                        cteturelit. Accusantium assumendlor sit nsecteturelit. Accusantium
                    </p>
                </div>
            </div>
        </div>

        <div class="flex transform w-[50rem]  transition duration-500 shadow rounded
            hover:scale-110 hover:bg-slate-300  justify-start items-center cursor-pointer">
            <div class=" min-w-[12rem]  p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                <h4>June 22, 2024</h4>
                <h4>July 23, 2024</h4>
            </div>
            <div class="flex flex-col justify-center max-h-[10rem] overflow-auto p-3">
                <h1 class="font-semibold">Beginning of OJT</h1>
                <div class=" max-h-[10rem] overflow-auto">
                    <p class="text-justify text-sm pr-5">

                    </p>
                </div>
            </div>
        </div>

    </div>
</section>


<dialog id="Act&shedModal" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] h-full  flex flex-col text-slate-700">
        <div  class=" card-title sticky flex justify-between  items-center mr-2">
            <h1 class="font-bold text-lg pl-5 pt-5 pb-0">Create activity and schedule</h1>
            <button class="btn btn-sm btn-circle btn-ghost " onclick="closeModalForm('Act&shedModal');resetNoteForm('act_n_schedForm')">✕</button>
        </div>
        <div class="p-4 h-full">
            <form id="act_n_schedForm" class=" h-full" enctype="multipart/form-data">
                <input type="hidden" name="actionType" value="new" id="action_type">
                <input type="hidden" value="" name="announcementID" id="announcementID">
                <div class="flex flex-col gap-8  overflow-auto h-[80vh]">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-start gap-2">
                                <label class="form-control w-full ">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Title</span>
                                    </div>
                                    <input type="text" required name="noteTitle" placeholder="Type here"
                                           class="  bg-slate-100 input input-bordered w-full " />
                                </label>
                            </div>
                            <div class="flex justify-start gap-2">
                                <label class="form-control w-full ">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Text body</span>
                                    </div>
                                    <textarea required  name="message" class="textarea textarea-success w-full" rows="5" cols="80" placeholder="Optional"></textarea>
                                </label>
                            </div>
                        </div>
                        <hr class=" m-3">
                        <h1 class="font-bold">Activity Dates</h1>
                        <div class="flex justify-start flex-wrap  gap-2 items-center ">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Start</span>
                                </div>
                                <input name="startDate"   type="date"  data-theme="light"
                                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">End</span>
                                </div>
                                <input name="endDate"   type="date"  data-theme="light"
                                       class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <hr class=" m-3">

                        <h1 class=" text-sm">Same day activity date</h1>
                        <div class="flex justify-start flex-wrap  gap-2 items-center ">
                            <label class="form-control w-full max-w-xs">
                                <input name="sameDayActDate" type="date"  data-theme="light"
                                       class="bg-slate-100 input input-bordered w-full max-w-xs"  />
                            </label>
                        </div>
                    </div>
                    <p id="new_adv_adminLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                    <div id="new_adv_adminBtn" class="flex justify-center m-5">
                        <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Create</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</dialog>