<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
session_start();
include '../DatabaseConn/databaseConn.php';
?>
<div class="px-9 pt-2 flex <?php echo $_SESSION['log_user_type'] === 'admin' ? 'justify-end' : 'justify-between'?>  items-stretch flex-wrap gap-5 pb-0 bg-transparent">
    <?php if ($_SESSION['log_user_type'] === 'adviser'):?>
        <button onclick="printStudentOJTSummary()" class="btn btn-neutral bg-slate-500 border-none text-slate-100">Export OJT summary <i class="fa-solid fa-file-export"></i></button>
    <?php endif;?>

    <button class="btn btn-neutral bg-slate-500 border-none text-slate-100" onclick="openModalForm('manageStudModalFormxls');resetStudentEditForm()">Import excel <i class="fa-solid fa-download"></i></button>
</div>



<div class="overflow-y-hidden relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9  flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <div class="flex w-full justify-between">
                <div class="w-50">
                    <select id="totalRecDis" onchange="get_studentUserList();
                    renderPage_lim(this.value,'nextBtn', 'prevBtn' )" class=" select-sm select select-bordered bg-slate-100 ">

                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>

                    </select>
                    <span class="text-xs">Entries per page</span>
                </div>
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searcbox" type="text" placeholder="Search" onkeyup="handleSearch('searcbox', 'studListTbl')">
                </div>
            </div>
        </div>
        <div class="block  px-9 overflow-auto h-[70vh] xl:h-[70vh]">
            <table id="studListTbl" class="w-full my-0 b text-sm  border border-none table table-xs" >
                <thead class="align-bottom z-20">
                <tr class="font-bold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                    <th onclick="sortTable(0, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">Student No.<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(1, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">Name<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(2, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">Program<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(3, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">Yr & Sec<span class="sort-icon text-xs"></span></th>

                    <?php if ($_SESSION['log_user_type'] == 'adviser'):?>
                        <th onclick="sortTable(4, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">OJT started<span class="sort-icon text-xs"></span></th>
                        <th onclick="sortTable(5, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">OJT Ended<span class="sort-icon text-xs"></span></th>

                    <?php elseif ($_SESSION['log_user_type'] == 'admin'):?>
                        <th onclick="sortTable(4, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">OJT center<span class="sort-icon text-xs"></span></th>
                        <th onclick="sortTable(5, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">OJT contact<span class="sort-icon text-xs"></span></th>
                        <th onclick="sortTable(6, 'studListTbl')" class="p-3 text-start w-32 cursor-pointer">OJT Adviser<span class="sort-icon text-xs"></span></th>

                    <?php endif;?>
                    <th class="p-3 text-end w-32 ">Action</th>
                </tr>
                </thead>
                <tbody id="studentsList" class="text-center text-slate-600">
                <!--          <tr class="border-b border-dashed last:border-b-0 p-3">
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
                              <td class="p-3 text-end w-[200px] break-words">
                                  <span class="font-semibold text-light-inverse text-md/normal">BSIT</span>
                              </td>
                              <td class="p-3 text-end">
                                  <a href="#" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                              </td>
                          </tr>-->
                <tr>
                    <td colSpan="9">
                        <span class="loading loading-spinner loading-lg"></span>
                    </td>
                </tr>

                </tbody>
            </table>


        </div>
        <div class="flex justify-center gap-5">
            <button id="prevBtn" onclick="get_studentUserList(); prevPage(this.id, 'nextBtn')" class="btn-neutral btn-sm btn font-semibold">Prev</button>
            <div class="text-center">
                <span id="pageInfo">Page 1</span>
            </div>
            <button id="nextBtn" onclick="get_studentUserList(); nextPage(this.id, 'prevBtn')" class="btn-neutral btn-sm btn font-semibold">Next</button>
        </div>
    </div>

</div>
<dialog id="manageStudModalForm" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] max-h-[40rem]  flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('manageStudModalForm');resetStudentEditForm()">✕</button>
            <h3 class="font-bold text-center text-lg p-5" id="studFormTitle">Add new student </h3>

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
                                <input oninput="validateInput(this)" required type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
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
                        <div class="flex justify-evenly gap-2 flex-wrap sm:flex-nowrap">
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

                            <label class="form-control w-full w-xs">
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
                        <div class="flex justify-start gap-2 flex-wrap sm:flex-nowrap">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Student number <span class="text-warning"> (Must be unique)</span></span>
                                </div>
                                <input type="number" min="0" required name="school_id" placeholder="XXXXXXXX" oninput="this.value = this.value.slice(0, 9)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT Adviser</span>
                                </div>
                                <select id="stud_adviser" onchange="loalAdvHandle_prog(this.value)" name="stud_adviser" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>
                                    <?php
                                    $adv_option_query = "SELECT ui.*, acc.*
                                 FROM tbl_user_info ui
                                 INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
                                 WHERE ui.user_type  = 'adviser' AND acc.status = 'active'";
                                    if ($_SESSION['log_user_type'] === 'adviser'){
                                        $adv_option_query.= ' AND ui.user_id = '.$_SESSION['log_user_id'];
                                    }
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


                        </div>
                        <div class="flex justify-evenly gap-2 flex-wrap sm:flex-nowrap">

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select name="stud_Program" id="stud_Program"  class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>

                                </select>

                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select name="progCourse" id="prog_course"  class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>

                                </select>

                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Year & Section</span>
                                </div>
                                <select id="stud_yrSection"   name="stud_Section" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>

                                </select>
                            </label>

                        </div>
                        <div class="flex justify-start gap-2 flex-wrap sm:flex-nowrap">

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT center</span>
                                </div>
                                <input type="text" name="stud_OJT_center" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT contact</span>
                                </div>
                                <input type="text" name="stud_ojtContact" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <div class="flex justify-start gap-2 flex-wrap sm:flex-nowrap" id>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                   <span class="label-text text-slate-700">Account email
                                       <span id="acc_section_indicator">

                                       </span>
                                   </span>
                                </div>
                                <input name="user_Email" required type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                <span id="default_passIndicator">

                                </span>

                            </label>

                            <!--<label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Password <span class="text-info"> (Predefined "CVSUOJT{Student ID}") </span>
                                </div>
                                <input autocomplete="off"  type="password" placeholder="Predefined password" data-theme="light"
                                       disabled class="disabled disabled:text-black input w-full max-w-xs" />
                            </label>-->
                        </div>

                        <input type="hidden" name="user_type" value="student">
                        <input type="hidden" name="user_id" >
                        <input type="hidden" name="studInfo" >
                        <div id="deaccSectionModal">



                        </div>
                        <div id="deactSectionLink" class="text-end">

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
<dialog id="manageStudModalFormxls" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem]   flex flex-col text-slate-700">
        <div  class=" card-title sticky ">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('manageStudModalFormxls');resetStudentEditForm()">✕</button>
            <h3 class="font-bold text-center text-lg p-5" id="studFormTitle">Add new student</h3>

        </div>
        <hr>
        <div  class=" card-title sticky flex justify-end pr-10">
            <div class="flex justify-center flex-col items-center">
                <div class="tooltip tooltip-bottom " data-tip="Download">
                    <a href="assets/Student_basic_info_list_format.xlsx" class="btn btn-circle btn-info btn-outline border-none"  id="exlFormat"><i class="fa-solid fa-download"></i></a>
                </div>
                <p class="cursor-pointer text-center text-sm " id="studFormTitle">Excel format</p>

            </div>

        </div>

        <div class="p-4 pt-0">
            <form id="studentFormxls"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-auto">
                    <div class="flex flex-col gap-5">
                        <div class="flex justify-start gap-2 flex-wrap sm:flex-nowrap">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT Adviser</span>
                                </div>
                                <select id="stud_adviser" onchange="loalAdvHandle_prog(this.value)" name="stud_adviser" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>
                                    <?php
                                    $adv_option_query = "SELECT ui.*, acc.*
                                 FROM tbl_user_info ui
                                 INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
                                 WHERE ui.user_type  = 'adviser' AND acc.status = 'active'";

                                    if ($_SESSION['log_user_type'] === 'adviser'){
                                        $adv_option_query.= ' AND ui.user_id = '.$_SESSION['log_user_id'];
                                    }
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

                        </div>
                        <div class="flex justify-evenly gap-2 flex-wrap sm:flex-nowrap">

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <select name="stud_Program" onchange="loadAvailableProgCourse(this.value) " id="stud_xlsProgram"  class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select</option>

                                </select>

                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Course</span>
                                </div>
                                <select name="progCourse" onchange="loadAvailableYearSec(this.value) "
                                        id="progCourse"  class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select program</option>

                                </select>

                            </label>

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Year & Section</span>
                                </div>
                                <select id="stud_xlsSection"   name="stud_Section" class="select select-bordered w-full bg-slate-100 " required>
                                    <option value="" selected disabled>Select course</option>

                                </select>
                            </label>
                        </div>

                        <div class="flex justify-evenly gap-2 flex-wrap sm:flex-nowrap">

                            <input required oninput="jsonExcelSheet(this.files[0])" class="file-input file-input-bordered file-input-success w-full max-w-xs"
                                   type="file" name="excelFile" accept=".xls,.xlsx">

                        </div>

                        <input type="hidden" name="user_type" value="student">
                        <input required type="hidden" id="excelStudData" name="excelStudData" value="">

                    </div>
                </div>
                <p id="newStudentXLXSLoader" class="text-center hidden">Please wait<br><span class="loading loading-dots loading-md text-slate-700"></span></p>
                   <div id="excelErrorNote" class="text-center text-error font-semibold text-xs">

                </div>
                <div id="newStudBtnxls" class="flex justify-center m-3">
                    <button id="stud_Submitxls" class="btn btn-success btn-outline w-1/4" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</dialog>


<dialog id="aa"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('aa')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-warning absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>New student account has been created!</span>
        </div>
    </div>
</dialog>

<dialog id="aasdasa"  class="modal  bg-black bg-opacity-10 " onclick="closeModalForm('aasdasa')">
    <div class="card bg-slate-50 w-[80vw]  sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
        <div role="alert" class="alert alert-error absolute top-50" >
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>New student account has been created!</span>
        </div>
    </div>
</dialog>

