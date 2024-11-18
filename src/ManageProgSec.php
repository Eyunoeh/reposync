<?php ?>




<section class="  overflow-auto   bg-white pl-5 pb-5 pr-5 pt-0">
    <div class=" flex items-center sticky top-0 p-5 bg-white shadow rounded z-50 justify-between ">
        <div class="">
            <a href="manageAcadYear.php" class=" font-light btn btn-neutral ">
                <u> Manage academic year</u>
            </a>
        </div>
        <div class="">
            <button onclick="openModalForm('ProgSecFormModal'); if (!document.getElementById('formSelect')) renderSelectformOption(); addCourseListner()" class=" btn btn-circle btn-success ">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

    </div>
</section>



<section class=" ml- sm:ml-0 flex justify-between sm:flex-nowrap flex-wrap sm:gap-5 h-[100vh] p-5">
    <div class="flex sm:w-[90%]  transition  shadow rounded
              justify-center  ">
        <div class="w-full  overflow-hidden hover:overflow-auto">
            <table class="table table-sm bordered" id="progam_tbl">
                <!-- head -->
                <thead class="w-full sticky top-0 shadow bg-slate-100 rounded text-slate-700">
                <tr>
                    <th onclick="sortTable(0,'progam_tbl')" class="cursor-pointer">Program code<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(1,'progam_tbl')" class="cursor-pointer">Program name<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(2,'progam_tbl')" class="cursor-pointer">Total OJT hours<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(3,'progam_tbl')" class="cursor-pointer"> Total course <span class="sort-icon text-xs"></span></th>
                    <th class="text-center">Action</th>

                </tr>
                </thead>
                <tbody id="programs">

                </tbody>
            </table>
            <div id="tableNoResProg" class="flex justify-center items-center">
            </div>
        </div>
    </div>
    <div class="flex sm:w-1/2 w-full  transition  shadow rounded
              justify-center  ">
        <div class="w-full overflow-hidden hover:overflow-auto">
            <h1></h1>
            <table class="table  table-sm" id="yr_sectbl">
                <!-- head -->
                <thead class="sticky top-0 shadow bg-slate-100 rounded text-slate-700">
                    <tr>
                        <th onclick="sortTable(0,'yr_sectbl')" class="text-center cursor-pointer">Year & Section<span class="sort-icon text-xs"></th>
                        <th class="text-center">Action </th>

                    </tr>
                </thead>
                <tbody id="yrSec">
                <!-- row 1 -->

                
                </tbody>
            </table>
            <div id="tableNoResYrSec" class="flex justify-center items-center">
            </div>
        </div>
    </div>

</section>

<dialog id="ProgSecFormModal" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem]   flex flex-col text-slate-700">
        <div  class=" card-title sticky flex justify-end" id="act_schedtitle">
            <button class=" btn btn-sm btn-circle btn-ghost "  onclick="closeModalForm('ProgSecFormModal'); removeSelectFormOption();">✕</button>
        </div>
        <div class="p-4 ">
            <form id="sectionProgramForm" class="overflow-y-auto h-full max-h-[87vh]" >
                <input type="hidden" name="action_type" value="">
                <input type="hidden" name="ID" value="">
                <div class="w-full flex justify-end" id="option">

                </div>
                <div class="flex flex-col gap-8  " id="SectionProgramFormInputs">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-start gap-2">
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Program Code</span>
                                    </div>
                                    <input type="text" required name="ProgramCode" placeholder="Type here" class="bg-slate-100 input input-bordered w-full" />
                                </label>
                            </div>
                            <div class="flex justify-start gap-2 w-full">
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Program Name</span>
                                    </div>
                                    <input type="text" required name="ProgramName" class="bg-slate-100 input input-bordered w-full" placeholder="Type here">
                                </label>
                            </div>
                            <div class=" flex justify-between w-full items-end">

                                <div class=" w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Course Code</span>
                                    </div>
                                    <input  type="text" required name="course_code" id="course_code" placeholder="Type here" class="disabled:text-black bg-slate-100 input input-bordered w-full max-w-xs"  />
                                </div>
                                <div class=" w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700">OJT hours</span>
                                    </div>
                                    <select id="OJT_hoursOption" required name="OJT_hoursOption" class="disabled:text-black bg-slate-100 select select-bordered w-full max-w-xs" >
                                        <option disabled selected>Select</option>
                                        <option value="150">150</option>
                                        <option value="200">200</option>
                                        <option value="240">240</option>
                                        <option value="300">300</option>
                                        <option value="486">486</option>
                                        <option value="600">600</option>
                                        <option value="640">640</option>
                                    </select>
                                </div>
                                <a class="btn btn-success " id="addCourseBtn">Add</a>
                                <input type="hidden" name="ojt_course_json" id="ojt_course_json">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="w-full my-5" id="progCourseSec">
                    <table class="table table-sm bordered" id="courseTbl">
                        <!-- head -->
                        <thead class="w-full sticky top-0 shadow bg-slate-100 rounded text-slate-700">
                        <tr>
                            <th  class="cursor-pointer">Course code</th>
                            <th  class="cursor-pointer"> OJT hours</th>
                            <th class="text-center">Action</th>

                        </tr>
                        </thead>
                        <tbody id="programCourses">


                        </tbody>
                    </table>
                    <div id="nocourseNote" class="flex justify-center items-center ">
                        <p class="text-sm text-slate-700 font-sans">No <p class="text-sm text-slate-700 font-sans">No selected course</p>

                    </div>
                </div>
                <hr class=" m-3">

                <p id="progyrsecLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                <div id="progyrsecLoaderbtn" class="flex justify-center ">
                    <button id="progYrSecSubmit" class="btn btn-success btn-outline w-1/4" >Add</button>
                </div>
            </form>

        </div>
        <div id="errNotifcotainer" onclick="resetAlertBox(this.id)"></div>
    </div>
</dialog>

<dialog id="ProgYrSecNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('ProgYrSecNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-info absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span id="ProgrYrSecNotifText"></span>
        </div>
    </div>
</dialog>