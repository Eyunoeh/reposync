<?php
session_start();
?>
<section class="overflow-auto flex sm:flex-row flex-col bg-clip-border gap-2 rounded-[.95rem]  m-5 h-[75vh] max-h-[30rem] text-slate-600">
    <section class="w-full border  <?php echo  isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'student'? 'sm:w-[70%]' :''?> bg-opacity-50 bg-white p-5 rounded shadow-2xl">
        <div class="card ">
            <div class="card-title mb-7 ">
                <h1 class="font-bold text-2xl text-warning font-sans">Activities & Schedule</h1>
            </div>
            <div class=" p-3 sm:p-5 overflow-auto flex flex-col max-h-[20rem] scroll-smooth">
                <div class="flex  ">
                    <div class=" w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                        <h4>June 22, 2024</h4>
                        <h4>July 23, 2024</h4>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Beginning of OJT</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.</p>
                    </div>
                </div>
                <div class="flex  ">
                    <div class=" w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                        <h4>June 22, 2024</h4>
                        <h4>July 23, 2024</h4>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Beginning of OJT</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.</p>
                    </div>
                </div>
                <div class="flex  ">
                    <div class=" w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                        <h4>June 22, 2024</h4>
                        <h4>July 23, 2024</h4>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Beginning of OJT</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.</p>
                    </div>
                </div>
                <div class="flex  ">
                    <div class=" w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                        <h4>June 22, 2024</h4>
                        <h4>July 23, 2024</h4>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Beginning of OJT</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.</p>
                    </div>
                </div>
                <div class="flex  ">
                    <div class=" w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                        <h4>June 22, 2024</h4>
                        <h4>July 23, 2024</h4>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Beginning of OJT</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.</p>
                    </div>
                </div>
                <div class="flex  ">
                    <div class=" w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                        <h4>June 22, 2024</h4>
                        <h4>July 23, 2024</h4>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Beginning of OJT</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.</p>
                    </div>
                </div>
                <div class="flex  ">
                    <div class=" w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">
                        <h4>June 22, 2024</h4>
                        <h4>July 23, 2024</h4>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Beginning of OJT</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php
    if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'student'):
    ?>
    <section class="w-full border sm:w-[30%] bg-white p-5 rounded shadow-2xl bg-opacity-50 ">
        <div class="card ">
            <div class="card-title mb-7">
                <h1>Adviser Notes</h1>
            </div>
            <div class=" overflow-auto flex flex-col max-h-[20rem] scroll-smooth">
                <div class="flex  ">
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Note title</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.
                        <p class="text-[12px] text-slate-400 text-end">3/20/2024 4:30pm </p>
                    </div>
                </div>
                <div class="flex  ">
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Note title</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.
                        <p class="text-[12px] text-slate-400 text-end">3/20/2024 4:30pm </p>
                    </div>
                </div>

                <div class="flex  ">
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Note title</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.
                        <p class="text-[12px] text-slate-400 text-end">3/20/2024 4:30pm </p>
                    </div>
                </div>

                <div class="flex  ">
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Note title</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.
                        <p class="text-[12px] text-slate-400 text-end">3/20/2024 4:30pm </p>
                    </div>
                </div>

                <div class="flex  ">
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Note title</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.
                        <p class="text-[12px] text-slate-400 text-end">3/20/2024 4:30pm </p>
                    </div>
                </div>

                <div class="flex  ">
                    <div class="flex flex-col justify-center">
                        <h1 class=" font-semibold ">Note title</h1>
                        <p class=" text-justify text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Accusantium assumenda at commodi deserunt error ipsum maxime mi.
                        <p class="text-[12px] text-slate-400 text-end">3/20/2024 4:30pm </p>
                    </div>
                </div>




            </div>
        </div>
    </section>
    <?php
    endif;
    ?>
</section>
