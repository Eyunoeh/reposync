


async function get_studentUserList() {

    let table_data = '';
    let log_userInfo = (await user_info()).data;

    // Fetch the student list
    const { response, data: student_list } = await $.ajax({
        url: '../ajax.php?action=getStudentsList',
        method: 'GET',
        dataType: 'json'
    });

    if (response === 1 && student_list.length > 0) {
        // Collect unique adviser info in one call
        let adviserInfoMap = {};
        await Promise.all([...new Set(student_list.map(s => s['adv_id']))].map(async adv_id => {
            adviserInfoMap[adv_id] = (await user_info(adv_id)).data;
        }));


        let isAdviser = log_userInfo['user_type'] === 'adviser';

        let org_studList = [];
        for (let i = 0; i < student_list.length; i++) {
            let student = student_list[i];

            let adviserMatches = student['adv_id'] === log_userInfo['user_id'];
            let showRow = isAdviser ? adviserMatches : true;

            if (showRow) {
                let adv_info = adviserInfoMap[student['adv_id']];


                org_studList.push({
                    user_id: student['user_id'],
                    student_id: student['enrolled_stud_id'],
                    student_name: student['first_name'] + ' ' +  student['last_name'] ,
                    student_program: student['program_code'],
                    student_yrSec: student['year'] + student['section'],
                    ojt_center: student['ojt_center'],
                    ojt_loc: student['ojt_location'],
                    adviser_id: student['adv_id'],
                    adviser_name: student['adv_id'] !== null ? adv_info['first_name']+ ' ' + adv_info['last_name'] : 'N/A'
                });
            }
        }

        let offset = (page_no - 1) * totalRecPerpage;
        total_page = Math.ceil( org_studList.length / totalRecPerpage);


        let paginatedList = org_studList.slice(offset, offset + totalRecPerpage);


        paginatedList.forEach(student => {
            table_data += `
            <tr class="bg-white">
                <td class="p-3 text-start text-wrap mx-w-32">
                    <span class=" text-light-inverse text-md/normal">${student['student_id']}</span>
                </td>
                <td class="p-3 text-start text-wrap mx-w-32">
                    <span class=" text-light-inverse text-md/normal">${student['student_name']}</span>
                </td>
                <td class="p-3 text-start text-wrap mx-w-32">
                    <span class=" text-light-inverse text-md/normal">${student['student_program']}</span>
                </td>
                <td class="p-3 text-start text-wrap mx-w-32">
                    <span class=" text-light-inverse text-md/normal">${student['student_yrSec']}</span>
                </td> 
                ${isAdviser ? '' : `
                <td class="p-3 text-start text-wrap mx-w-32">
                    <span class=" text-light-inverse text-md/normal ">${student['ojt_center']}</span>
                </td>
                <td class="p-3 text-start text-wrap mx-w-32">
                    <span class=" text-light-inverse text-md/normal "> ${student['ojt_loc']}</span>
                </td>
                <td class="p-3 text-start text-wrap mx-w-32">
                    <span class=" text-light-inverse text-md/normal ">${student['adviser_name']}</span>
                </td>`}
                <td class="p-3 text-end text-wrap mx-w-32">
                    <a href="#" onclick="openModalForm('manageStudModalForm');editUserStud_Info(${student['user_id']})"   class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                        <i class="fa-solid fa-circle-info"></i>
                    </a>
                </td>
            </tr>`;
        });

        $('#studentsList').html(table_data);

    } else {
        table_data = `<tr><td colspan="9">No Result</td></tr>`;
    }
    $('#tableadvLoader').empty();

}

async function editUserStud_Info(user_id) {
    let stud_info = await user_info(user_id)
    if (stud_info.response  === 1){
        let data = stud_info.data;
        $('#studentForm input[name="user_Fname"]').val(data.first_name);
        $('#studentForm input[name="user_Mname"]').val(data.middle_name);
        $('#studentForm input[name="user_Lname"]').val(data.last_name);
        $('#studentForm input[name="user_address"]').val(data.address);
        $('#studentForm input[name="contactNumber"]').val(data.contact_number);
        $('#studentForm input[name="school_id"]').val(data.enrolled_stud_id);
        $('#studentForm input[name="stud_OJT_center"]').val(data.ojt_center);
        $('#studentForm input[name="stud_ojtLocation"]').val(data.ojt_location);
        $('#studentForm input[name="user_id"]').val(data.user_id);
        $('#studentForm select[name="stud_Program"]').val(data.program_id);
        $('#studentForm select[name="stud_Section"]').val(data.year_sec_Id);
        $('#studentForm select[name="stud_adviser"]').val(data.adv_id);

        $('#studentForm input[name="user_Email"]').val(data.email);

        renderDeacAccLink('manageStudModalForm', 'get_studentUserList');
        $('#deactivate_acc').attr('data-user_id', data.user_id);



        $('#studFormTitle').html('Edit student information');
        $('#stud_Submit').html('Save');
        $('#acc_section_indicator').empty();
        $('#default_passIndicator').empty();


        if (data.sex === "male") {
            $('#studentForm input[name="user_Sex"][value="male"]').prop('checked', true);
        } else if (data.sex === "female") {
            $('#studentForm input[name="user_Sex"][value="female"]').prop('checked', true);
        }
    }

}


function resetStudentEditForm(){
    $('#studentForm').trigger("reset");
    $('#studFormTitle').html('Add new student');
    $('#studentForm input[name="user_id"]').val('');
    $('#stud_Submit').html('Submit');
    $('#deaccSectionModal').empty()
    $('#deactSectionLink').empty();
    $('#acc_section_indicator').html(`<div class="tooltip tooltip-right ml-2 z-10 cursor-pointer" data-tip="System will notify the user about the account through email">
                                                <i class="fa-solid fa-circle-info"></i>
                                            </div>
                                            `)
    $('#default_passIndicator').html(`<div class="label">
                                        <span class="label-text text-slate-700">Default password: <span class="text-info">"CVSUOJT{Student ID}" </span>
                                    </div>`)
}

