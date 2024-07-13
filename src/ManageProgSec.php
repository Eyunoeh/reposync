<?php ?>
<div class=" flex justify-end pr-7" >
    <button onclick="openModalForm('ProgSecFormModal'); if (!document.getElementById('formSelect')) renderSelectformOption();" class=" btn btn-circle btn-success ">
        <i class="fa-solid fa-plus"></i>
    </button>
</div>
<section class=" ml- sm:ml-0 flex justify-between  sm:gap-5 h-[100vh] ">
    <div class="flex sm:w-[90%]  transition  shadow rounded
              justify-center  ">
        <div class="w-full  overflow-hidden hover:overflow-auto">
            <table class="table ">
                <!-- head -->
                <thead class="w-full sticky top-0 shadow bg-slate-100 rounded text-slate-700">
                <tr>
                    <th>Program Code</th>
                    <th>Program Name</th>
                    <th class="text-center">Action</th>

                </tr>
                </thead>
                <tbody id="programs">
                <!-- row 1 -->
                <tr>
                    <td>BSIT</td>
                    <td>Quality Control Specialist</td>
                    <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>
                <!-- row 2 -->
                <tr class="hover">

                    <td>BSCS</td>
                    <td>Desktop Support Technician</td>
                    <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>

                </tr>
                <!-- row 3 -->
                <tr>
                    <td>BSBM</td>
                    <td>Tax Accountant</td>
                    <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex w-1/2  transition  shadow rounded
              justify-center  ">
        <div class="w-full overflow-hidden hover:overflow-auto">
            <h1></h1>
            <table class="table  ">
                <!-- head -->
                <thead class="sticky top-0 shadow bg-slate-100 rounded text-slate-700">
                    <tr>
                        <th class="text-center">Year & Section</th>
                        <th class="text-center">Action</th>

                    </tr>
                </thead>
                <tbody id="yrSec">
                <!-- row 1 -->
                <tr>
                    <td>4A</td>
                    <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>
                <!-- row 2 -->
                <tr class="hover">
                    <td>4B</td>
                    <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>
                <!-- row 3 -->
                <tr>
                    <td>4C</td>
                    <td class="text-center"><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>
                
                </tbody>
            </table>
        </div>
    </div>

</section>

<dialog id="ProgSecFormModal" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem]   flex flex-col text-slate-700">
        <div  class=" card-title sticky flex justify-end" id="act_schedtitle">
            <button class=" btn btn-sm btn-circle btn-ghost "  onclick="closeModalForm('ProgSecFormModal'); removeSelectFormOption();">✕</button>
        </div>
        <div class="p-4 ">
            <form id="sectionProgramForm" class="" >
                <input type="hidden" name="action_type" value="">
                <input type="hidden" name="ID" value="">
                <div class="w-full flex justify-end" id="option">

                </div>
                <div class="flex flex-col gap-8  overflow-auto" id="SectionProgramFormInputs">
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
                            <div class="flex justify-start gap-2">
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Program Name</span>
                                    </div>
                                    <input type="text" required name="ProgramName" class="bg-slate-100 input input-bordered w-full" placeholder="Type here">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class=" m-3">
                <p id="progyrsecLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                <div id="progyrsecLoaderbtn" class="flex justify-center ">
                    <button id="progYrSecSubmit" class="btn btn-success btn-outline w-1/4" >Add</button>
                </div>
            </form>
        </div>
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