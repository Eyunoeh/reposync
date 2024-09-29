render_AdvUsertList();

function addAssignment() {
    let assignAdvInput = document.getElementById("assignedAdvList");
    let assignments = assignAdvInput.value ? JSON.parse(assignAdvInput.value) : [];

    let programSelect = document.getElementById("assignedProg");
    let programValue = programSelect.value;

    let yearSectionSelect = document.getElementById("assignedYearSec");
    let yearSectionValue = yearSectionSelect.value;

    let combination = { program: programValue, section: yearSectionValue };

    // Check for duplicates
    let exists = assignments.some(item => item.program === combination.program && item.section === combination.section);

    if (exists) {
        Alert('formAlertbox', 'This program and section is already assigned!', 'warning');
        return;
    }

    // Add the new assignment
    assignments.push(combination);

    // Update hidden input value with the updated assignments array
    assignAdvInput.value = JSON.stringify(assignments);

    // Display the updated assignments list
    displayAssignments(assignments);
}

function removeAssignedAdvisory(programValue, yearSectionValue) {
    let assignAdvInput = document.getElementById("assignedAdvList");
    let assignments = assignAdvInput.value ? JSON.parse(assignAdvInput.value) : [];

    // Filter out the selected program and year section combination
    assignments = assignments.filter(item => !(item.program === programValue && item.section === yearSectionValue));

    // Update hidden input value with the updated assignments array
    assignAdvInput.value = JSON.stringify(assignments);

    // Display the updated assignments list
    displayAssignments(assignments);
}

function displayAssignments(assignments) {
    const assignedList = document.getElementById("hndl_adv_list");

    let programSelect = document.getElementById("assignedProg");
    let yearSectionSelect = document.getElementById("assignedYearSec");
    if (assignments.length > 0){
        assignedList.innerHTML = assignments.map(item => {
            let displayProgram = programSelect.options[Array.from(programSelect.options).findIndex(opt => opt.value === item.program)].text;
            let displaySection = yearSectionSelect.options[Array.from(yearSectionSelect.options).findIndex(opt => opt.value === item.section)].text;

            return `<li class="flex justify-evenly">
                <span class="flex-grow ">${displayProgram} - ${displaySection}</span> 
                <a class="text-error hover:cursor-pointer hover:opacity-50 transition-all" onclick="removeAssignedAdvisory('${item.program}', '${item.section}')">
                    <i class="fa-solid fa-circle-xmark"></i>
                </a>
                </li>
<hr>`;
        }).join('');
    }else {
        assignedList.innerHTML = `<li>Empty advisory</li>`;
    }

}



async function editAdvInfo(user_id){

    try {

        let userInfo = await user_info(user_id);
        let adv_handle_stud = await getAdv_list();

        if (userInfo.response === 1 && adv_handle_stud.response === 1) {
            let  user_data = userInfo.data;
            let adv_handle_stud_data = adv_handle_stud.data;
            let assignments = [];

            let hndle_advList = '';


            if (adv_handle_stud_data.length > 0) {
                for (let i = 0; i < adv_handle_stud_data.length; i++) {
                    if (user_id === adv_handle_stud_data[i].user_id) {
                        hndle_advList += `<li>${adv_handle_stud_data[i].program_code} ${adv_handle_stud_data[i].year} ${adv_handle_stud_data[i].section}</li>`;

                        assignments.push({
                            program: `${adv_handle_stud_data[i].program_id}`,
                            section: `${adv_handle_stud_data[i].year_sec_Id}`
                        });

                    }

                }

            }

            $('#assignedAdvList').val(JSON.stringify(assignments));
            displayAssignments(assignments)




            $('#assignedAdvList').html(hndle_advList);




            $('#deaccSectionModal').html(`<dialog id="deactivate_adv_acc" class="modal bg-black  bg-opacity-40 ">
                            <div class="card bg-slate-50 w-[80vw] absolute top-10 sm:w-[30rem] max-h-[35rem]  flex flex-col text-slate-700">
                                <div  class=" card-title sticky ">
                                    <h3 class="font-bold text-center text-lg  p-5">Are you sure you want to deactivate this OJT Adviser account?</h3>
                                </div>
                                <div class="p-4 w-full flex justify-evenly">
                                    <a id="deactivate_adv" class="btn  btn-error w-1/4 " onclick="closeModalForm('deactivate_adv_acc');deactivate_account(this.getAttribute('data-user_id'), 'editAdv_admin')" data-user_id="">Deactivate</a>
                                    <a class="btn  btn-info  w-1/4 " onclick="closeModalForm('deactivate_adv_acc')">Close</a>
                                </div>
                            </div>
                        </dialog>`);
            $('#deactSectionLink').html(`
<a class="transition-all text-error font-bold font-sans cursor-pointer text-end pr-6 m-3 hover:opacity-50 active:text-slate-500" onclick="openModalForm('deactivate_adv_acc')">Deactivate account? </a>`)

            $('#admin_adv_Form input[name="user_Fname"]').val(user_data.first_name);
            $('#admin_adv_Form input[name="user_Mname"]').val(user_data.middle_name);
            $('#admin_adv_Form input[name="user_Lname"]').val(user_data.last_name);
            $('#admin_adv_Form input[name="user_address"]').val(user_data.address);
            $('#admin_adv_Form input[name="contactNumber"]').val(user_data.contact_number);

            $('#admin_adv_Form input[name="user_Email"]').val(user_data.email);
            $('#admin_adv_Form input[name="user_id"]').val(user_data.user_id);
            $('#admin_adv_Form select[name="user_type"]').val(user_data.user_type);
            $('#deactivate_adv').attr('data-user_id', user_data.user_id);
            $('#admin_adv_Submit').html('Save');

            if (user_data.sex === "Male") {
                $('#admin_adv_Form input[name="user_Sex"][value="Male"]').prop('checked', true);
            } else if (user_data.sex === "Female") {
                $('#admin_adv_Form input[name="user_Sex"][value="Female"]').prop('checked', true);
            }


        } else {
            console.error('Error: Unexpected response format or no data');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
function getAdv_list (user_id = null) {
    return new Promise((resolve, reject) => {

        $.ajax({
            url: '../ajax.php?action=getAdvisers',
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}

async function render_AdvUsertList() {
    try {
        let response = await getAdv_list();
        if (response.response === 1 && response.data.length > 0) {
            let advisers = response.data.reduce((acc, adviser) => {
                let { user_id, first_name, last_name, program_code, year, section, totalStud } = adviser;
                if (!acc[user_id]) acc[user_id] = { name: `${first_name} ${last_name}`,user_id : user_id, programs: [] };
                acc[user_id].programs.push({ program_code, year, section, totalStud });
                return acc;
            }, {});





            let table_data = Object.values(advisers).map(({ name, user_id, programs }) => `
                    <tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="text-light-inverse text-md/normal">${name}</span>
                        </td>
                        <td class="p-3 text-center">${programs.map(p => `${p.program_code}`).join('<hr>')}</td>
                        <td class="p-3 text-center">${programs.map(p => `${p.year} ${p.section}`).join('<hr>')}</td>
                        <td class="p-3 text-center">${programs.map(p => `${p.totalStud}`).join('<hr>')}</td>
                        <td class="p-3 text-end">
                            <a onclick="openModalForm('newAdvierDialog');editAdvInfo(${user_id})" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                                <i class="fa-solid fa-circle-info"></i>
                            </a>
                        </td>
                    </tr>
                `).join('');

            $('#tableadvLoader').empty();
            $('#advList').html(table_data);
        } else {
            $('#tableadvLoader').html('<h1>No record</h1>');
        }
    } catch (error) {
        console.error('Error:', error);
    }

}

function clearAdviserForm(){
    $('#admin_adv_Form').trigger("reset");
    $('#admin_adv_Form select[name="user_id"]').val('');
    $('#admin_adv_Submit').html('Submit');
    $('#deaccSectionModal').empty()
    $('#deactSectionLink').empty();
    $('#assignedAdvList').val('');
    $('#hndl_adv_list').html(`<li>Empty advisory</li>`)
}





