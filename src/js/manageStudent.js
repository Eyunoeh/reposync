


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
        if (paginatedList.length > 0 ){




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
        }else {
            table_data = `<tr><td colspan="9">No Result</td></tr>`;

        }



    } else {
        table_data = `<tr><td colspan="9">No Result</td></tr>`;
    }

    $('#studentsList').html(table_data);

}

async function editUserStud_Info(user_id) {
    let stud_info = await user_info(user_id)
    if (stud_info.response  === 1){
        let data = stud_info.data;
        loadStudentprogSecDropdown(data.adv_id)
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

async function jsonExcelSheet(excel_file) {
    const fileReader = new FileReader();

    // Fetch student and adviser data
    const { data: student_list } = await $.ajax({
        url: '../ajax.php?action=getStudentsList',
        method: 'GET',
        dataType: 'json'
    });
    const adv_list = await getAdv_list();
    const adv_listData = adv_list.data;

    // Extract unique lists of IDs, contact numbers, and emails
    const existingStudID = student_list.map(student => student.enrolled_stud_id);

    const existingCnum = [...new Set([
        ...student_list.map(student => student.contact_number),
        ...adv_listData.map(advisor => advisor.contact_number)
    ])];
    console.log(existingCnum);
    const existingEmail = [...new Set([
        ...student_list.map(student => student.email),
        ...adv_listData.map(advisor => advisor.email)
    ])];
    console.log(existingEmail);

    // Process file on load
    fileReader.onload = (event) => {
        const data = event.target.result;
        const workbook = XLSX.read(data, { type: "binary" });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet);
        let excelErrorNote = '';

        const unqStud_id = new Set();
        const unqCnum = new Set();
        const unqAccEmail = new Set();

        // Validate rows
        jsonData.forEach(row => {
            const requiredKeys = [
                'Student No', 'First name', 'Middle name', 'Last name',
                'Contact No', 'Address', 'Sex', 'OJT Center',
                'OJT Location', 'Acc Email'
            ];

            // Check for required keys
            for (const key of requiredKeys) {
                if (!row.hasOwnProperty(key)) {
                    excelErrorNote = `No '${key}' column found.`;
                    return;
                }
            }

            const studNo = row['Student No'];
            const studCnum = row['Contact No'];
            const studEmail = row['Acc Email'];


            // Check for duplicates within the Excel file
            if (unqStud_id.has(studNo)) {
                excelErrorNote = `Student No: ${studNo} is duplicate.`;
                return;
            }
            if (unqCnum.has(studCnum)) {
                excelErrorNote = `Contact No: ${studCnum} is duplicate.`;
                return;
            }
            if (unqAccEmail.has(studEmail)) {
                excelErrorNote = `Acc Email: ${studEmail} is duplicate.`;
                return;
            }

            // Check for duplicates with existing data
            if (existingStudID.includes(studNo)) {
                excelErrorNote = `StudNo ${studNo} already exists in the system.`;
                return;
            }
            if (existingCnum.map(String).includes(String(studCnum))) {
                excelErrorNote = `Contact No: ${studCnum} already exists in the system.`;
                return;
            }

            if (existingEmail.includes(studEmail)) {
                excelErrorNote = `Acc Email: ${studEmail} already exists in the system.`;
                return;
            }

            // Add unique values to sets
            unqStud_id.add(studNo);
            unqCnum.add(studCnum);
            unqAccEmail.add(studEmail);
        });

        // Output the results based on validation
        if (!excelErrorNote) {
            $('#excelStudData').val(JSON.stringify(jsonData));
            $('#excelErrorNote').empty();
            enable_button('stud_Submitxls')
        } else {
            $('#excelStudData').val('');
            $('#excelErrorNote').html(excelErrorNote);
            disable_button('stud_Submitxls');
        }
    };
    if (excel_file){
        fileReader.readAsArrayBuffer(excel_file);
    }else {
        $('#excelErrorNote').empty()
    }

}



function resetStudentEditForm(){
    $('#studentForm').trigger("reset");
    $('#studentFormxls').trigger("reset");
    $('#excelErrorNote').empty();
    enable_button('stud_Submitxls')
    $('#excelStudData').val('');
    $('#studFormTitle').html('Add new student');
    $('#studentForm input[name="user_id"]').val('');
    $('#stud_Submit').html('Submit');
    $('#deaccSectionModal').empty()
    $('#deactSectionLink').empty();

    $('#stud_xlsSection').html(`<option value="" selected disabled>Select OJT adviser</option>`);
    $('#stud_xlsProgram').html(`<option value="" selected disabled>Select OJT adviser</option>`);
    $('#stud_Program').html(`<option value="" selected disabled>Select OJT adviser</option>`);
    $('#stud_Section').html(`<option value="" selected disabled>Select OJT adviser</option>`);
    $('#acc_section_indicator').html(`<div class="tooltip tooltip-right ml-2 z-10 cursor-pointer" data-tip="System will notify the user about the account through email">
                                                <i class="fa-solid fa-circle-info"></i>
                                            </div>
                                            `)
    $('#default_passIndicator').html(`<div class="label">
                                        <span class="label-text text-slate-700">Default password: <span class="text-info">"CVSUOJT{Student ID}" </span>
                                    </div>`)
}

async function loadStudentprogSecDropdown(adv_id) {
    const  adv_list = await $.ajax({
        url: '../ajax.php?action=getAdvisers',
        method: 'GET',
        dataType: 'json'
    });


    let prog_yearSec = adv_list.data.reduce((acc, adviser) => {
        let { user_id, program_code, program_id, year, section, year_sec_Id } = adviser;
        if (!acc[user_id]) {
            acc[user_id] = {
                adviser_id: user_id,
                program: program_code,
                program_id: program_id,
                yr_sec: []
            };
        }
        acc[user_id].yr_sec.push({ year, section, year_sec_Id });
        return acc;
    }, {});

    let program_option = ``;
    let yr_sec_option = ``;

    if (prog_yearSec[adv_id]) {
        program_option += `<option value="${prog_yearSec[adv_id].program_id !== null ? prog_yearSec[adv_id].program_id : 'N/A'}" selected>
${prog_yearSec[adv_id].program_id !== null ? prog_yearSec[adv_id].program : 'N/A'}</option>`;
        yr_sec_option = prog_yearSec[adv_id].yr_sec.map(yearsec =>
            `<option value="${yearsec.year_sec_Id !== null ? yearsec.year_sec_Id : 'N/A'}">
${yearsec.year_sec_Id !== null ? yearsec.year + yearsec.section : 'N/A'}</option>`
        ).join('');
    }
    $('#stud_Program').html(program_option);
    $('#stud_xlsProgram').html(program_option);
    $('#stud_Section').html(yr_sec_option);
    $('#stud_xlsSection').html(yr_sec_option);
}
