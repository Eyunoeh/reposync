<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: dashboard.php");
    exit();
}
session_start();
include '../DatabaseConn/databaseConn.php';
?>

<div class="px-9 pt-2 flex justify-end items-stretch flex-wrap  pb-0 bg-transparent">
    <button class="btn btn-neutral bg-slate-500 border-none text-slate-100" onclick="openModalForm('newAdvierDialog');clearAdviserForm()">Create new</button>

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
                <table id="AdvListTbl" class="w-full my-0 border-neutral-200 table" >
                    <thead class="align-bottom z-20">
                    <tr class="font-bold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                        <th onclick="sortTable(0, 'AdvListTbl')" class="p-3 text-start w-32 cursor-pointer">Name<span class="sort-icon text-xs"></th>
                        <th onclick="sortTable(1, 'AdvListTbl')" class="p-3 text-center w-32 cursor-pointer">Program<span class="sort-icon text-xs"></th>
                        <th onclick="sortTable(2, 'AdvListTbl')" class="p-3 text-center w-32 cursor-pointer">Year and Section<span class="sort-icon text-xs"></th>
                        <th onclick="sortTable(3, 'AdvListTbl')" class="p-3 text-center w-32 cursor-pointer">Total advisory<span class="sort-icon text-xs"></th>
                        <th class="p-3 text-end w-32 ">Action</th>
                    </tr>
                    </thead>
                    <tbody id="advList" class="text-center text-slate-600 w-32 ">


                    </tbody>

                </table>
                <div id="tableadvLoader" class="flex justify-center items-center">
                    <span class="loading loading-spinner loading-lg"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<dialog id="newAdvierDialog"  class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] h-[40rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky flex justify-end mr-2">
            <button class="btn btn-sm btn-circle btn-ghost " onclick="closeModalForm('newAdvierDialog');clearAdviserForm()">✕</button>
        </div>
        <div class="p-4">
            <form id="admin_adv_Form"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-auto h-[30rem]">
                    <div id="adv_formFirstPage" class="flex flex-col gap-2 ">
                        <div class="flex justify-evenly sm:gap-2 flex-wrap sm:flex-nowrap">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">First name</span>
                                </div>
                                <input oninput="validateInput(this)" required type="text"  name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Middle name</span>
                                </div>
                                <input oninput="validateInput(this)" type="text"  name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Last name</span>
                                </div>
                                <input oninput="validateInput(this)" type="text" required name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>

                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Address</span>
                                </div>
                                <input type="text" required name="user_address" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Contact number</span>
                                </div>
                                <input type="number" min="0" required name="contactNumber" placeholder="09XXXXXXXXX" oninput="this.value = this.value.slice(0, 11)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Sex</span>
                                </div>
                                <div class="flex justify-start gap-2">
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Male</label>
                                        <input required type="radio" name="user_Sex" value="male" class="radio bg-gray-300"  />
                                    </div>
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Female</label>
                                        <input required type="radio" name="user_Sex" value="female" class="radio bg-gray-300" />
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div id="adv_formSecPage" class="flex flex-col gap-2 justify-center h-full " >
                            <div class="flex justify-start gap-2">
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Account email
                                        <div class="tooltip tooltip-right ml-2 z-10 cursor-pointer" data-tip="System will notify the user about the account through email">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </div>
                                        </span>

                                    </div>
                                    <input name="user_Email" type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                            </div>
                        </div>

                        <hr class="m-2">

                        <h1>Handle Advisory</h1>


                        <div class="flex justify-start gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select required name="assignedProg" id="assignedProg"  class=" select-sm bg-slate-100 select-bordered
                                 select w-full max-w-xs" >
                                    <?php
                                    $sql = "SELECT * FROM  program";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option value="'.$row['program_id'].'">'.$row['program_code'].'</option>
                                               ';

                                    }

                                    ?>

                                </select>
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Year and Section</span>
                                </div>
                                <select required name="assignedYearSec" id="assignedYearSec" class="select-sm  bg-slate-100 select-bordered
                                 select w-full max-w-xs" >
                                    <?php
                                    $sql = "SELECT * FROM  section";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option value="'.$row['year_sec_Id'].'">'.$row['year'].$row['section'].'</option>';
                                    }
                                    ?>
                                </select>
                            </label>
                            <div class="flex justify-center items-end flex-grow">
                                <div>
                                    <a class="btn  btn-md btn-neutral" onclick="addAssignment()">Add</a>
                                </div>
                            </div>
                        </div>
                        <div class="pl-10 p-5" id="hndl_adv_list">
                            <li>Empty advisory</li>


                        </div>

                    </div>
                    <div id="deaccSectionModal">



                    </div>
                    <div id="deactSectionLink" class="text-end">

                    </div>


                </div>
                <hr class="mb-5">
                <div id="hidden_inputs">
                    <input name="user_id" type="hidden" value="">
                    <input name="user_type" type="hidden" value="adviser">
                </div>
                <input type="hidden" id="assignedAdvList" name="assignedAdvList">


                <div id="formAlertbox" onclick="resetAlertBox(this.id)"></div>
                <div id="adv_formsbtBTN" >
                    <p id="new_adv_adminLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                    <div id="new_adv_adminBtn" class="flex items-end justify-center m-3">
                        <button id="admin_adv_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</dialog>




