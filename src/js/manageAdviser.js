

/*
function addAdvisoryAssignment() {
    let assignAdvInput = document.getElementById("assignedAdvList");
    let assignments = assignAdvInput.value ? JSON.parse(assignAdvInput.value) : [];

    let programSelect = document.getElementById("assignedProg");
    let programValue = programSelect.value;

    let yearSectionSelect = document.getElementById("assignedYearSec");
    let yearSectionValue = yearSectionSelect.value;

    let combination = { program: programValue, section: yearSectionValue };

    // Check for dif prog
    let diffprog = assignments.some(item => item.program !== combination.program)
    let prog_yr_sec_exist = assignments.some(item => item.program === combination.program && item.section === combination.section);

    if (diffprog){
        Alert('formAlertbox', 'Select only one program!', 'warning');
        return;
    }

    // Check for duplicates
    if (prog_yr_sec_exist) {
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
*/



async function editAdvInfo(user_id){
    try {

        let adv_handle_stud = await getAdv_list();
        if (adv_handle_stud.response === 1) {
            let adv_list = adv_handle_stud.data;
            if (adv_list.length > 0) {
                for (let i = 0; i < adv_list.length; i++) {
                    if (user_id === adv_list[i].user_id) {
                        let user_data = adv_list[i]
                        $('#admin_adv_Form input[name="user_Fname"]').val(user_data.first_name);
                        $('#admin_adv_Form input[name="user_Mname"]').val(user_data.middle_name);
                        $('#admin_adv_Form input[name="user_Lname"]').val(user_data.last_name);
                        $('#admin_adv_Form input[name="user_address"]').val(user_data.address);
                        $('#admin_adv_Form input[name="contactNumber"]').val(user_data.contact_number);

                        $('#admin_adv_Form input[name="user_Email"]').val(user_data.email);
                        $('#admin_adv_Form input[name="user_id"]').val(user_data.user_id);
                        $('#admin_adv_Form select[name="user_type"]').val(user_data.user_type);

                        $('#admin_adv_Form select[name="assignedProg"]').val(user_data.program_id);


                        renderDeacAccLink('newAdvierDialog', 'render_AdvUsertList');

                        $('#deactivate_acc').attr('data-user_id', user_data.user_id);

                        $('#admin_adv_Submit').html('Save');

                        if (user_data.sex === "male") {
                            $('#admin_adv_Form input[name="user_Sex"][value="male"]').prop('checked', true);
                        } else if (user_data.sex === "female") {
                            $('#admin_adv_Form input[name="user_Sex"][value="female"]').prop('checked', true);
                        }
                        console.log(user_data)
                    }

                }

            }

        } else {
            console.error('Error: Unexpected response format or no data');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
function getAdv_list () {
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
            let advisers = response.data;

            // Generate table rows using map
            let table_data = advisers.map(adviser => {
                return `
                    <tr class="border-b border-dashed last:border-b-0 p-3 hover">
                        <td class="p-3 text-start">
                            <span class="text-light-inverse text-md/normal">${adviser.first_name} ${adviser.last_name}</span>
                        </td>
                        <td class="p-3 text-center">${adviser.program_code || 'N/A'}</td>
                    <td class="p-3 text-center">
    ${adviser.handleAdvisory.length > 0
                    ? adviser.handleAdvisory.map(adv => adv.yearSec ).join('<hr>') : 'N/A'}
</td>
<td class="p-3 text-center">
    ${adviser.handleAdvisory.length > 0
                    ? adviser.handleAdvisory.map(adv => adv.total_students).join('<hr>') : 'N/A'}
</td>

                        <td class="p-3 text-end">
                            <a onclick="openModalForm('newAdvierDialog');editAdvInfo(${adviser.user_id})" 
                               class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                                <i class="fa-solid fa-circle-info"></i>
                            </a>
                        </td>
                    </tr>`;
            }).join('');


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
   // $('#assignedAdvList').val('');
    $('#hndl_adv_list').html(`<li>Empty advisory</li>`)
}






