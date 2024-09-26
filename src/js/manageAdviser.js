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






