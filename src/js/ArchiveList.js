




function retrieveArchiveUserInfo(id){
    $.ajax({
        url: '../ajax.php?action=getArchiveUsers&archive_id=' + encodeURIComponent(id),
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.response === 1){
                let data = response.data[0];
                if (data.user_type === 'student'){

                    $('#ArhiveForm').html(`<form id="EditStudentForm"  enctype="multipart/form-data">
                    <div class="flex flex-col gap-8 mb-2 overflow-y-auto h-[25rem]">
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-evenly gap-2">
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">First name</span>
                                    </div>
                                    <input disabled type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Middle name</span>
                                    </div>
                                    <input disabled type="text"  name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Last name</span>
                                    </div>
                                    <input type="text" disabled name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                            </div>
                            <div class="flex justify-evenly gap-2">
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Address</span>
                                    </div>
                                    <input type="text" disabled name="user_address" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Contact number</span>
                                    </div>
                                    <input disabled type="number" min="0" required name="contactNumber" placeholder="09XXXXXXXXX" oninput="this.value = this.value.slice(0, 11)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>

                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Sex</span>
                                    </div>
                                    <div class="flex justify-start gap-2">
                                        <div class="flex justify-center items-center flex-col">
                                            <label class="text-sm">Male</label>
                                            <input disabled type="radio" name="user_Sex" value="Male" class="radio bg-gray-300"  />
                                        </div>
                                        <div class="flex justify-center items-center flex-col">
                                            <label class="text-sm">Female</label>
                                            <input disabled type="radio" name="user_Sex" value="Female" class="radio bg-gray-300" />
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="flex justify-evenly gap-2">
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">School ID number</span>
                                    </div>
                                    <input  type="number" min="0" disabled name="school_id" placeholder="XXXXXXXX" oninput="this.value = this.value.slice(0, 9)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Program</span>
                                    </div>
                                    <input name="stud_Program" disabled class="input input-bordered w-full bg-slate-100 " >
                                  

                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Year & Section</span>
                                    </div>
                                    <input  name="stud_Section" class="input input-bordered w-full bg-slate-100 " disabled>
                          
                                
                                </label>

                            </div>
                            
                            <hr class="w-full border bg-slate-700 mt-10 ">


                            <div class="flex justify-start gap-2">


                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Account email</span>
                                    </div>
                                    <input disabled name="user_Email" type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                            </div>
                        </div>
                    </div>
                </form>`);
                    $('#EditStudentForm input[name="user_Fname"]').val(data.first_name);
                    $('#EditStudentForm input[name="user_Mname"]').val(data.middle_name);
                    $('#EditStudentForm input[name="user_Lname"]').val(data.last_name);
                    $('#EditStudentForm input[name="user_address"]').val(data.address);
                    $('#EditStudentForm input[name="contactNumber"]').val(data.contact_number);
                    $('#EditStudentForm input[name="school_id"]').val(data.school_id);
                    $('#EditStudentForm input[name="user_Email"]').val(data.email);
                    $('#EditStudentForm input[name="stud_Program"]').val(data.program_code);
                    $('#EditStudentForm input[name="stud_Section"]').val(data.year + data.section);
                    if (data.sex === "Male") {
                        $('#EditStudentForm input[name="user_Sex"][value="Male"]').prop('checked', true);
                    } else if (data.sex === "Female") {
                        $('#EditStudentForm input[name="user_Sex"][value="Female"]').prop('checked', true);
                    }

                }else{
                    $('#ArhiveForm').html(`<form id="EditAdviserForm"  enctype="multipart/form-data">
                    <div class="flex flex-col gap-8 mb-2 overflow-y-auto h-[25rem]">
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-evenly gap-2">
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">First name</span>
                                    </div>
                                    <input disabled required type="text" name="user_Fname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Middle name</span>
                                    </div>
                                    <input disabled type="text"  name="user_Mname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Last name</span>
                                    </div>
                                    <input disabled type="text" required name="user_Lname" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                            </div>
                            <div class="flex justify-evenly gap-2">
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Address</span>
                                    </div>
                                    <input disabled type="text" required name="user_address" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Contact number</span>
                                    </div>
                                    <input disabled type="number" min="0" required name="contactNumber" placeholder="09XXXXXXXXX" oninput="this.value = this.value.slice(0, 11)" class="bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>

                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Sex</span>
                                    </div>
                                    <div class="flex justify-start gap-2">
                                        <div class="flex justify-center items-center flex-col">
                                            <label class="text-sm">Male</label>
                                            <input disabled type="radio" name="user_Sex" value="Male" class="radio bg-gray-300"  />
                                        </div>
                                        <div class="flex justify-center items-center flex-col">
                                            <label class="text-sm">Female</label>
                                            <input disabled type="radio" name="user_Sex" value="Female" class="radio bg-gray-300" />
                                        </div>
                                    </div>
                                </label>
                            </div>

                    
                            <hr class="w-full border bg-slate-700 mt-10 ">


                            <div class="flex justify-start gap-2">

                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text text-slate-700">Account email</span>
                                    </div>
                                    <input disabled name="user_Email" type="email" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                                </label>
                            </div>
                        </div>
                    </div>
                </form>`);
                    $('#EditAdviserForm input[name="user_Fname"]').val(data.first_name);
                    $('#EditAdviserForm input[name="user_Mname"]').val(data.middle_name);
                    $('#EditAdviserForm input[name="user_Lname"]').val(data.last_name);
                    $('#EditAdviserForm input[name="user_address"]').val(data.address);
                    $('#EditAdviserForm input[name="contactNumber"]').val(data.contact_number);
                    $('#EditAdviserForm input[name="user_Email"]').val(data.email);
                    if (data.sex === "Male") {
                        $('#EditAdviserForm input[name="user_Sex"][value="Male"]').prop('checked', true);
                    } else if (data.sex === "Female") {
                        $('#EditAdviserForm input[name="user_Sex"][value="Female"]').prop('checked', true);
                    }
                }
                $('#retrieve_btn').attr('data-id', data.acc_id);




            }
        },error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    })
}//renderFOrm data
function retrieveArchiveNarrativeReportInfo(id){
    $.ajax({
        url: '../ajax.php?action=getArchiveNarrative&archive_id=' + encodeURIComponent(id),
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.response === 1){
                let data = response.data[0];
                document.getElementById('dlLink').href='NarrativeReportsPDF/'+ data.narrative_file_name;
                let startSchYear = ""
                let endSchYear = "";
                if (data.sySubmitted !== 'N/A') {
                    let years = data.sySubmitted.split(',');
                    startSchYear = years[0].trim();
                    endSchYear = years[1].trim();
                }
                $('#ArhiveForm').html(`  
                <form id="EditNarrativeReportsReqForm"  enctype="multipart/form-data">
                <div class="flex flex-col gap-8 mb-2 overflow-auto h-[25rem]">
                    <div class="flex flex-col gap-2">
               
                        <div class="flex justify-evenly gap-2">

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">First name</span>
                                </div>
                                <input type="text" required name="first_name" disabled placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Middle name</span>
                                </div>
                                <input type="text"  disabled name="middle_name" placeholder="Optional" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Last name</span>
                                </div>
                                <input type="text" disabled required name="last_name" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">School ID number <span class="text-warning"> (Must be unique)</span></span>
                                </div>
                                <input disabled type="number" min="0" oninput="this.value = this.value.slice(0, 9)" required name="school_id" placeholder="XXXXXXXX" maxlength="8" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700 text-center">Sex</span>
                                </div>
                                <div class="flex justify-start gap-2">
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Male</label>
                                        <input disabled type="radio" name="stud_Sex" value="Male" class="radio bg-gray-300" checked />
                                    </div>
                                    <div class="flex justify-center items-center flex-col">
                                        <label class="text-sm">Female</label>
                                        <input disabled type="radio" name="stud_Sex" value="Female" class="radio bg-gray-300" />
                                    </div>
                                </div>
                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">OJT Adviser</span>
                                </div>
                                <input name="ojt_adviser" disabled class="input inpur-bordered w-full bg-slate-100 " required>
                    
                            </label>
                        </div>
                        <input type="hidden" name="narrative_id" value="">
                        <input type="hidden" name="ojt_adviser" value="">

                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Program</span>
                                </div>
                                <input  disabled name="program" class="input inpur-bordered w-full bg-slate-100 ">
                          

                            </label>

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Section</span>
                                </div>
                                <input disabled  name="section" class="input input-bordered w-full bg-slate-100 ">
                              

                            </label>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">School Year</span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <input disabled type="number" required name="startYear" oninput="this.value = this.value.slice(0, 4)" class="bg-slate-100 input input-bordered w-full max-w-xs" placeholder="0000" />
                                    <p class="text-center items-center font-bold text-lg"> - </p>
                                    <input disabled type="number" required name="endYear" oninput="this.value = this.value.slice(0, 4)" class="bg-slate-100 input input-bordered w-full max-w-xs" placeholder="0000" />
                                </div>

                            </label>

                        </div>
                        <div class="flex justify-evenly gap-2">
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Student Company / Institution</span>
                                </div>
                                <input disabled type="text" required name="companyName" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>

                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text text-slate-700">Training Hours</span>
                                </div>
                                <input disabled type="number" required name="trainingHours" placeholder="Type here" class=" bg-slate-100 input input-bordered w-full max-w-xs" />
                            </label>
                        </div>
                
                    </div>

         
                </div>
                </form>`);


                $('#retrieve_btn').attr('data-id', data.narrative_id);


               document.querySelector('#EditNarrativeReportsReqForm input[name="startYear"]').value = startSchYear;
               document.querySelector('#EditNarrativeReportsReqForm input[name="endYear"]').value = endSchYear;
                document.querySelector('#EditNarrativeReportsReqForm input[name="trainingHours"]').value = data.training_hours;
                document.querySelector('#EditNarrativeReportsReqForm input[name="companyName"]').value = data.company_name;

                 document.querySelector('#EditNarrativeReportsReqForm input[name="first_name"]').value = data.first_name;
                 document.querySelector('#EditNarrativeReportsReqForm input[name="middle_name"]').value = data.middle_name;
                 document.querySelector('#EditNarrativeReportsReqForm input[name="last_name"]').value = data.last_name;
                 document.querySelector('#EditNarrativeReportsReqForm input[name="school_id"]').value = data.stud_school_id;
                 document.querySelector('#EditNarrativeReportsReqForm input[name="program"]').value = data.program;
                 document.querySelector('#EditNarrativeReportsReqForm input[name="section"]').value = data.section;
                 if (data.sex === "Male") {
                     document.querySelector('#EditNarrativeReportsReqForm input[name="stud_Sex"][value="Male"]').checked = true;
                 } else if (data.sex === "Female") {
                     document.querySelector('#EditNarrativeReportsReqForm input[name="stud_Sex"][value="Female"]').checked = true;
                 }
                 document.querySelector('#EditNarrativeReportsReqForm input[name="ojt_adviser"]').value = data.OJT_adviser_Fname + ' ' + data.OJT_adviser_Lname;
            }

        },error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    })
} ////renderFOrm data



function UnarchiveData(id, route) {
    let endpoint;

    if (route === 'Users') {
        endpoint = 'recoverUser';
    } else if (route === 'NarrativeReports') {
        endpoint = 'recoverNarrativeReport';
    } else {
        window.location.href = 'dashboard.php';
        return;
    }

    $.ajax({
        url: '../ajax.php?action=' + endpoint + '&archived_id=' + encodeURIComponent(id),
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.response === 1) {
                closeModalForm('ArhiveModal')
                Alert('notifBox', response.message, 'info');
                setTimeout(function (){
                    window.location.href = 'manage_ArchiveList.php?route=' + route;

                },3000)
            } else {
                alert('Error: ' + response.message || 'Unknown error occurred.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            alert('An error occurred while processing the request.');
        }
    });
}

async function archiveNarrative(narrative_id){
    const archiveCall = await $.ajax({
        url: '../ajax.php?action=archiveNarrative&narrative_id=' + narrative_id,
        method: 'GET',
        dataType: 'json'
    });

    if (archiveCall.response === 1){
        dashboard_student_NarrativeReports()
    }

    closeModalForm('archiveNarrativeModal')
    Alert('notifBox', archiveCall.message, 'info')

}



