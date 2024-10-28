<?php
session_start();
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>
<section class=" overflow-auto flex sm:flex-row flex-col  gap-2 mt-2 mx-5  ">
    <section class="bg-white  bordered sm:border-none w-full
    h-full rounded
    <?php echo isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] == 'student' ? 'sm:w-[60%]' : '' ?>
     p-5">
        <div class="card-title flex items-center justify-between">
            <div class="">
                <h1 class="font-bold text-2xl text-warning font-sans">Activity and Schedule</h1>
            </div>
        </div>
        <div class="card-body flex flex-col items-center h-[70vh] sm:p-5 overflow-hidden hover:overflow-auto scroll-smooth gap-5" id="actSched">
<!--            <div class="flex min-w-[40rem] <?php /*echo  (isset($_SESSION['log_user_type']) &&
            $_SESSION['log_user_type'] == 'student' ? 'w-[40rem]' : 'w-[50rem]') */?>
            shadow rounded transition duration-500 transform
            hover:scale-110 hover:bg-slate-300 cursor-pointer justify-start items-center">
                    <div class="w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-start text-sm ">
                        <h4 class="text-start">1/22/2024</h4>
                    </div>
                    <div class="flex flex-col justify-center max-h-[10rem] overflow-auto ">
                        <h1 class="font-semibold">OJT asdasd</h1>
                        <div class="max-h-[10rem] transition duration-100 overflow-hidden hover:overflow-auto ">
                            <p class="text-justify text-sm pr-5 break-words"></p>
                        </div>
                    </div>

            </div>-->


        </div>



    </section>

    <?php
    if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'student'):
    ?>


        <section class="bg-white  bordered sm:border-none w-full h-full rounded sm:w-[40%] p-5">
            <div class="card-title flex items-center justify-between">
                <div class="">
                    <h1 class="font-bold text-2xl text-slate-700 font-sans">Adviser Notes</h1>
                </div>
            </div>
            <div class="card-body flex flex-col items-center h-[70vh] sm:p-5 overflow-hidden hover:overflow-auto scroll-smooth gap-5" id="studNotes">


            </div>



        </section>
<!--    <section class="bg-white bordered sm:border-none w-full h-full rounded sm:w-[40%] p-5">
        <div class="card ">
            <div class="card-title mb-7">
                <h1>Adviser Notes</h1>
            </div>
            <div class="card-body flex flex-col items-center h-[70vh]
            sm:p-5 overflow-hidden hover:overflow-auto scroll-smooth gap-5 " id="studNotes">

            </div>
        </div>
    </section>-->
    <?php
    endif;
    ?>
</section>
