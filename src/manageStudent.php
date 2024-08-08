<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
session_start();
include '../DatabaseConn/databaseConn.php';
?>
<div class="px-9 pt-2 flex justify-end items-stretch flex-wrap  pb-0 bg-transparent">
<!--    <button class="btn btn-neutral btn-sm bg-slate-500 border-none text-slate-100 " >Export accounts</button>
-->    <button class="btn btn-neutral bg-slate-500 border-none text-slate-100" onclick="openModalForm('newStudentdialog')">New Student</button>
</div>

<div class="overflow-y-hidden relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9  flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-start">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searcbox" type="text" placeholder="Search" onkeyup="handleSearch('searcbox', 'studListTbl')">
                </div>
            </form>
        </div>
        <div class="block  px-9">
            <div class="overflow-auto h-[70vh] xl:h-[70vh]">
                <table id="studListTbl" class="w-full my-0 border-neutral-200 text-sm" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                        <?php if ($_SESSION['log_user_type'] == 'admin'):?>
                            <th class="p-3 text-start ">School ID</th>
                            <th class="p-3 text-start ">Name</th>
                            <th class="p-3 text-start ">OJT Adviser</th>
                            <th class="p-3 text-end ">Training Hours</th>

                            <th class="p-3 text-end ">Program</th>
                            <th class="p-3 text-end ">Company</th>
                            <th class="p-3 text-end ">Action</th>
                        <?php elseif ($_SESSION['log_user_type'] == 'adviser'):?>
                            <th class="p-3 text-start ">School ID</th>
                            <th class="p-3 text-start ">Name</th>
                            <th class="p-3 text-end ">Training Hours</th>
                            <th class="p-3 text-end ">Program</th>
                            <th class="p-3 text-end ">Company</th>
                            <th class="p-3 text-end ">Action</th>

                        <?php endif;?>
                    </tr>
                    </thead>
                    <tbody id="studentsList" class="text-center text-slate-600">
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">210101279</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">first_name last_name</span>
                        </td>

                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">Male</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">4A</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">BSIT</span>
                        </td>
                        <td class="p-3 text-end">
                            <a href="#" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<dialog id="newStudentdialog" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[40rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('newStudentdialog')">✕</button>
            <h3 class="font-bold text-center text-lg  p-5">Add new student </h3>
        </div>
        <div class="p-4">
            <form id="studentForm"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-auto h-[27rem]">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-evenly gap-2 flex-wrap sm:flex-nowrap">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">First name</span>
                                </div>
                                <input required type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Middle name</span>
                                </div>
                                <input type="text"  name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Last name</span>
                                </div>
                                <input type="text" required name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
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
                                        <input required type="radio" name="user_Sex" value="Male" class="radio bg-gray-300"  />
                                    </div>
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Female</label>
                                        <input required type="radio" name="user_Sex" value="Female" class="radio bg-gray-300" />
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">School ID number <span class="text-warning"> (Must be unique)</span></span>
                                </div>
                                <input type="number" min="0" required name="school_id" placeholder="XXXXXXXX" oninput="this.value = this.value.slice(0, 9)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select name="stud_Program" required class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>
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
                                    <span class="label-text text-slate-700">Year & Section</span>
                                </div>
                                <select  name="stud_Section" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>
                                    <?php
                                    $sql = "SELECT * FROM  section";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option value="'.$row['section_id'].'">'.$row['year'].''.$row['section'].'</option>';
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT Adviser</span>
                                </div>
                                <select name="stud_adviser" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select adviser</option>
                                    <?php
                                    $adv_option_query = "SELECT ui.*, acc.*
                                 FROM tbl_user_info ui
                                 INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
                                 WHERE ui.user_type  = 'adviser' AND acc.status = 'active'";
                                    $result = $conn->query($adv_option_query);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['user_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No users found</option>";
                                    }
                                    ?>


                                </select>
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Company Name</span>
                                </div>
                                <input type="text" name="stud_compName" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Training Hours</span>
                                </div>
                                <input type="text" name="stud_TrainingHours" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Account email</span>
                                </div>
                                <input name="user_Email" required type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <input type="hidden" name="user_type" value="student">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Password <span class="text-info"> (Predefined "CVSUOJT{Student ID}") </span>
                                </div>
                                <input autocomplete="off"  type="password" placeholder="Predefined password" data-theme="light"
                                       disabled class="disabled disabled:text-black input w-full max-w-xs" />
                            </label>
                        </div>
                    </div>
                </div>
                <p id="newStudentLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>

                <div id="newStudBtn" class="flex justify-center m-3">
                    <button id="stud_Submit" class="btn btn-success btn-outline w-1/4" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</dialog>
<dialog id="editStuInfo" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('editStuInfo')">✕</button>
            <h3 class="font-bold text-center text-lg  p-5">Edit student info</h3>
        </div>
        <div class="p-4">
            <form id="EditStudentForm"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-y-auto h-[25rem]">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">First name</span>
                                </div>
                                <input required type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Middle name</span>
                                </div>
                                <input type="text"  name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Last name</span>
                                </div>
                                <input type="text" required name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
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
                                        <input required type="radio" name="user_Sex" value="Male" class="radio bg-gray-300"  />
                                    </div>
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Female</label>
                                        <input required type="radio" name="user_Sex" value="Female" class="radio bg-gray-300" />
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">School ID number <span class="text-warning"> (Must be unique)</span></span>
                                </div>
                                <input type="number" min="0" required name="school_id" placeholder="XXXXXXXX" oninput="this.value = this.value.slice(0, 9)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select name="stud_Program" required class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>
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
                                    <span class="label-text text-slate-700">Year & Section</span>
                                </div>
                                <select  name="stud_Section" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>
                                    <?php
                                    $sql = "SELECT * FROM  section";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()){
                                        echo '<option value="'.$row['section_id'].'">'.$row['year'].''.$row['section'].'</option>';
                                    }
                                    ?>
                                </select>
                            </label>

                        </div>
                        <div class="flex justify-evenly gap-2">

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT Adviser</span>
                                </div>
                                <select name="stud_adviser" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select adviser</option>
                                    <?php
                                    $adv_option_query = "SELECT ui.*, acc.*
                                 FROM tbl_user_info ui
                                 INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
                                 WHERE ui.user_type  = 'adviser' AND acc.status = 'active'";
                                    $result = $conn->query($adv_option_query);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['user_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No users found</option>";
                                    }
                                    ?>


                                </select>
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Company Name</span>
                                </div>
                                <input type="text" name="stud_compName" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Training Hours</span>
                                </div>
                                <input type="text" name="stud_TrainingHours" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <hr class="w-full border bg-slate-700 mt-10 ">


                        <div class="flex justify-start gap-2">


                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Account email</span>
                                </div>
                                <input name="user_Email" type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>

                        </div>
                        <a class="transition-all text-error font-bold font-sans cursor-pointer text-end pr-6 m-3 hover:opacity-50 active:text-slate-500" onclick="openModalForm('deactivate_account-stud')">Deactivate account? </a>

                    </div>
                </div>
                <input type="hidden" name="user_type" value="student">

                <input name="user_id" type="hidden" value="">

                <p id="editStudentLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>

                <div id="editStudBtn" class="flex justify-center m-3 gap-2.5 ">
                    <button id="update_stud_btn" class="btn btn-info btn-outline w-1/2" >Update</button>
                    <!--<button id="archive_stud_btn" class="btn btn-error btn-outline" >Archive</button>-->
                </div>
            </form>
        </div>
        <dialog id="deactivate_account-stud" class="modal bg-black  bg-opacity-40 ">
            <div class="card bg-slate-50 w-[80vw] absolute top-10 sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
                <div  class=" card-title sticky ">
                    <h3 class="font-bold text-center text-lg  p-5">Are you sure you want to deactivate student account?</h3>
                </div>
                <div class="p-4 w-full flex justify-evenly">
                    <a id="deactivate_stud_acc" class="btn  btn-error w-1/4 " onclick="closeModalForm('deactivate_account-stud');deactivate_account(this.getAttribute('data-user_id'), 'editStuInfo')" data-user_id="">Deactivate</a>
                    <button class="btn  btn-info  w-1/4 " onclick="closeModalForm('deactivate_account-stud')">Close</button>
                </div>
            </div>
        </dialog>

    </div>
</dialog>

<dialog id="NewStudentNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('NewStudentNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-success absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>New student account has been created!</span>
        </div>
    </div>
</dialog>
<dialog id="EditStudentNotif"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('EditStudentNotif')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-info absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Student information has been updated!</span>
        </div>
    </div>
</dialog>

