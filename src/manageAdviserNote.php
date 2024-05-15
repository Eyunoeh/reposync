<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
include '../DatabaseConn/databaseConn.php';
session_start();

?>


<section class="  overflow-auto   bg-white pl-5 pb-5 pr-5 pt-0 h-full rounded ">
    <div class=" flex items-center sticky top-0 p-5 bg-white shadow rounded z-50 justify-between ">
        <div class="">
            <h1 class="font-bold text-2xl text-warning font-sans">Notes</h1>
        </div>
        <div class="">
            <button class="btn btn-circle btn-success bg-opacity-70 " id="NewNote" onclick="openModalForm('Notes');"><i class="fa-solid fa-plus"></i></button>
        </div>
    </div>
    <div class="w-full flex justify-center">

    </div>
    <div class=" card-body  flex sm:flex-row flex-col flex-wrap justify-start
    p-3 sm:p-5 overflow-hidden sm:overflow-x-auto  scroll-smooth gap-10" id="AdviserNotes">

        <div class="transform w-full md:w-[18rem] overflow-auto transition duration-500 shadow rounded
            hover:scale-110 hover:bg-slate-300  justify-center items-center cursor-pointer p-3 h-[10rem]">
            <h1 class=" font-semibold ">Note title</h1>
            <p class="text-start text-sm">Loremipsum dolor sit amet, consectetur adipisicing
                elit. Accusantium assumenda at
            <p class="text-[12px]  text-slate-400 text-end">3/20/2024 4:30pm </p>
        </div>
    </div>
</section>


<dialog id="Notes" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[40rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('Notes');">✕</button>
            <h3 class="font-bold text-center text-lg  p-5">Students note</h3>
        </div>
        <div class="p-4">
            <form id="NotesForm"  >
                <input type="hidden" name="actionType" value="new" id="action_type">
                <input type="hidden" value="" name="announcementID" id="announcementID">
                <div class="flex flex-col gap-8 mb-2 overflow-auto ">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-start gap-2">
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-slate-700 font-bold">Title</span>
                                </div>
                                <input type="text" required name="noteTitle" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full " />
                            </label>
                        </div>
                        <div class="flex justify-start gap-2">
                            <label class="form-control w-full ">
                                <div class="label">
                                    <span class="label-text text-slate-700 font-bold">Description</span>
                                </div>
                                <textarea required  name="message" class="textarea textarea-success w-full" rows="5" cols="100" placeholder="Message..."></textarea>
                            </label>
                        </div>
                    </div>
                </div>
                <p id="notes" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                <div id="noteSubmit" class="flex justify-center m-3">
                    <button id="submit_btn" class="btn btn-success bg-opacity-40 btn-outline w-1/2" >OK</button>
                </div>
            </form>
        </div>
    </div>
</dialog>

