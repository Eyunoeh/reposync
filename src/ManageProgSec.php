<?php ?>
<div class=" flex justify-end pr-7" >
    <button onclick="openModalForm('ProgSecFormModal'); if (!document.getElementById('formSelect')) renderSelectformOption();" class=" btn btn-circle btn-success ">
        <i class="fa-solid fa-plus"></i>
    </button>
</div>
<section class=" ml- sm:ml-0 flex justify-between  sm:gap-5 h-[100vh] ">
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
</section>

<dialog id="ProgSecFormModal" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem]   flex flex-col text-slate-700">
        <div  class=" card-title sticky" id="act_schedtitle">
            <h1 class="font-bold text-center text-lg  p-2">Select Form</h1>
            <button class="absolute right-2 btn btn-sm btn-circle btn-ghost "  onclick="closeModalForm('ProgSecFormModal');removeTrashButton(); removeSelectFormOption();">✕</button>
        </div>
        <div class="p-4 ">
            <form id="sectionProgramForm" class="" enctype="multipart/form-data">
                <input type="hidden" name="action_type" value="">
                <input type="hidden" name="ID" value="">
                <div class="w-full flex justify-end" id="option">
                    <select id="formSelect" class="select  select-bordered w-full max-w-xs">
                        <option value="newProg">Program</option>
                        <option value="newyrSec">Year and Section</option>
                    </select>
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
                    <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</dialog>

