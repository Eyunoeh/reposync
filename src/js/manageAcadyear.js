document.getElementById('ManageAcadYearForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission
    let endpoint, notifType;
    const formData = new FormData(this); // Collect form data

    if ($('#action_type').val() === 'edit') {
        endpoint = 'updateAy';
        notifType = 'info';
    } else {
        endpoint = 'newAy';
        notifType = 'success';
    }


    $.ajax({
        url: '../ajax.php?action='+ endpoint,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.response === 1){
                Alert('notif', response.message, notifType)
                closeModalForm('ManageAcadYear')
                displayAcadYears();
                manageAcadYearReset()


            }else {
                Alert('errNotifcotainer', response.message, 'warning')
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);

        }
    });
});

$('#addCourseBtn').on('click', async function (e) {
    // Collect inputs
    const programSelectedText = $('#program option:selected').text();
    const courseSelectedText = $('#program_course option:selected').text();
    const courseSelected = $('#program_course').val();
    const semester = $('#semester').val();
    const ayStartYear = $('#aystartYear').val();
    const ayEndYear = $('#ayendYear').val();
    const ay = `${ayStartYear}, ${ayEndYear}`;
    const checkedYrSec = Array.from(document.querySelectorAll('.dynamic-checkbox:checked'))
        .map(checkbox => checkbox.value);

    // Validation
    const missingInputs = [];
    if (!ayStartYear) missingInputs.push('Academic Year Start is empty');
    if (!ayEndYear) missingInputs.push('Academic Year End is empty');
    if (!semester) missingInputs.push('Semester not selected');
    if (!programSelectedText) missingInputs.push('Program not selected');
    if (!courseSelected) missingInputs.push('Course not selected');
    if (checkedYrSec.length === 0) missingInputs.push('No Year/Section selected');

    if (missingInputs.length > 0) {
        Alert('errNotifcotainer', missingInputs[0], 'warning');
        return;
    }

    // Retrieve existing data
    const AyAvailableCourse = $('#Ay_availableCourse');
    const existingData = AyAvailableCourse.val() ? JSON.parse(AyAvailableCourse.val()) : [];

    // Add new data
    const newData = {
        program_Code: programSelectedText,
        course_code: courseSelectedText,
        course_code_id: courseSelected,
        semester: semester,
        ay: ay,
        yearSec: checkedYrSec
    };
    const existingCourse_id = existingData.find(item =>
        item.course_code_id === newData.course_code_id
    );
    if (existingCourse_id){
        Alert('errNotifcotainer', 'Program course already added', 'warning');
        return;
    }

    existingData.push(newData);

    // Update hidden input
    AyAvailableCourse.val(JSON.stringify(existingData));

    // Fetch year/section mappings
    await displaySelectedAvaiableProgCourseSec(existingData)
    document.querySelectorAll('.dynamic-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });

});


async function displaySelectedAvaiableProgCourseSec(json_AvailableProgCourseSec){
    if (json_AvailableProgCourseSec.length > 0){
        const { data: yrSecs } = await $.ajax({
            url: '../ajax.php?action=getYrSecJson',
            method: 'GET',
            dataType: 'json'
        });
        const yearSecMap = yrSecs.reduce((acc, item) => {
            acc[item.year_sec_Id] = `${item.year}${item.section}`;
            return acc;
        }, {});

        const tableRows = json_AvailableProgCourseSec.map(openCourse => {
            const displayYrSec = openCourse.yearSec
                .map(yrSec => yearSecMap[yrSec] || yrSec)
                .join(', ');

            return `
            <tr>
                <td>${openCourse.program_Code}</td>
                <td>${openCourse.course_code}</td>
                <td>${displayYrSec}</td>
                <td>
                    <a onclick="removeItem('${openCourse.course_code_id}')" class="cursor-pointer text-error p-2">
                        <i class="fa-solid fa-minus"></i>
                    </a>
                </td>
            </tr>`;
        }).join('');

        // Update the table
        $('#ay_openProgCourse').html(tableRows);
        $('#nocourseNote').empty();

    }else {
        $('#ay_openProgCourse').empty();
        $('#nocourseNote').html(`<p class="text-sm text-slate-700 font-sans">No selected program course</p>`)
    }
}
function removeItem(courseCodeId) {
    // Retrieve existing data
    const AyAvailableCourse = $('#Ay_availableCourse');
    const existingData = AyAvailableCourse.val() ? JSON.parse(AyAvailableCourse.val()) : [];
    const updatedData = existingData.filter(item => item.course_code_id !== courseCodeId);
    AyAvailableCourse.val(JSON.stringify(updatedData));
    // Refresh the table with updated data
    displaySelectedAvaiableProgCourseSec(updatedData);
}

async function render_ProgOptions() {
    let { response, data: programs } = await $.ajax({
        url: '../ajax.php?action=getProgJSON',
        method: 'GET',
        dataType: 'json'
    });

    let program_options = `<option disabled selected>Select</option>`;

    if (Array.isArray(programs) && programs.length > 0) {
        programs.forEach(program => {
            program_options += `<option value="${program.program_id}">${program.program_code}</option>`;
        });
    }


    $('#program').html(program_options);
}
async function render_CourseOptions(program_id) {
    let { data: programs } = await $.ajax({
        url: '../ajax.php?action=getProgJSON',
        method: 'GET',
        dataType: 'json'
    });

    let course_ids = [];
    let course_names = [];
    let course_options = '';

    if (Array.isArray(programs) && programs.length > 0) {
        programs.forEach(program => {
            if (String(program_id) === String(program.program_id)) {
                course_ids = program.courses_id.split(",").map(id => id.trim());
                course_names = program.courses.split(",").map(name => name.trim());
            }
        });
    }


    if (course_ids.length === course_names.length) {
        for (let i = 0; i < course_ids.length; i++) {
            course_options += `<option value="${course_ids[i]}">${course_names[i]}</option>`;
        }
    } else {
        course_options = `<option disabled>Course data mismatch</option>`;
    }

    if (!course_options) {
        course_options = `<option disabled>No courses available</option>`;
    }

    $('#program_course').html(course_options);
}


async function yrSecCheckboxesOptions() {
    let { data: yr_secs } = await $.ajax({
        url: '../ajax.php?action=getYrSecJson',
        method: 'GET',
        dataType: 'json'
    });

    if (Array.isArray(yr_secs) && yr_secs.length > 0) {
        // Sort by concatenated year and section
        yr_secs.sort((a, b) => {
            const aValue = `${a.year}${a.section}`;
            const bValue = `${b.year}${b.section}`;
            return aValue.localeCompare(bValue); // Sort alphabetically
        });

        const yr_secCheckboxes = yr_secs.map(yr_sec => `
            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">${yr_sec.year}${yr_sec.section}</span>
                    <input type="checkbox" value="${yr_sec.year_sec_Id}" class="checkbox dynamic-checkbox" />
                </label>
            </div>
        `).join('');

        $('#yrSecOptions').html(yr_secCheckboxes);
    } else {
        $('#yrSecOptions').html('<p>No options available</p>');
    }
}
async function editAy(acadYearID) {
    // Fetch year-section-course associations
    let { data: acadYearsryrSecCourses } = await $.ajax({
        url: '../ajax.php?action=avaialbleyrSecCourse&acadYearID=' + encodeURIComponent(acadYearID),
        method: 'GET',
        dataType: 'json'
    });

    // Fetch program mappings
    let { data: programs } = await $.ajax({
        url: '../ajax.php?action=getProgJSON',
        method: 'GET',
        dataType: 'json'
    });

    // Map course IDs to program and course details
    let mapPrograms = programs.reduce((map, program) => {
        let program_code = program.program_code;
        let program_courses_id = program.courses_id.split(',').map(id => id.trim());
        let program_courses = program.courses.split(',').map(course => course.trim());

        program_courses_id.forEach((course_id, index) => {
            map[course_id] = {
                course_code: program_courses[index] || "Unknown",
                program_code: program_code
            };
        });
        return map;
    }, {});

    // Group year-section associations by course_id
    let groupedByCourse = acadYearsryrSecCourses.reduce((group, item) => {
        let course_id = item.course_code_id;
        let yrSec_id = item.year_sec_Id;

        if (!group[course_id]) {
            group[course_id] = [];
        }
        group[course_id].push(yrSec_id);
        return group;
    }, {});


    // Fetch academic year details
    let { data: acadYears } = await $.ajax({
        url: '../ajax.php?action=AcadYears',
        method: 'GET',
        dataType: 'json'
    });

    let ayDetails = acadYears.find(acadYear => acadYear.id === acadYearID) || {};
    let aySemester = ayDetails.Semester || "Unknown";
    let ayStarting = ayDetails.ayStarting || "Unknown";
    let ayEnding = ayDetails.ayEnding || "Unknown";

    let existingData = [];
    Object.keys(groupedByCourse).forEach(course_id => {
        if (mapPrograms[course_id]) {
            existingData.push({
                program_Code: mapPrograms[course_id].program_code,
                course_code: mapPrograms[course_id].course_code,
                course_code_id: course_id,
                semester: aySemester,
                ay: `${ayStarting}-${ayEnding}`,
                yearSec: groupedByCourse[course_id]
            });
        }
    });
    $('#Ay_availableCourse').val(JSON.stringify(existingData));
    $('#ManageAcadYearForm input[name="aystartYear"]').val(ayStarting);
    $('#ManageAcadYearForm input[name="ayendYear"]').val(ayEnding);
    $('#ManageAcadYearForm select[name="semester"]').val(aySemester);
    $('#ManageAcadYearForm input[name="action_type"]').val('edit');
    $('#ManageAcadYearForm input[name="ay_ID"]').val(acadYearID);
    $('#progYrSecSubmit').html(`Save`);




    displaySelectedAvaiableProgCourseSec(existingData);


}


async function displayAcadYears(){
    let { data: acadYears } = await $.ajax({
        url: '../ajax.php?action=AcadYears',
        method: 'GET',
        dataType: 'json'
    });
    let acadYearstbl_data = '';
    if (acadYears.length === 0){
        $('#AcadYears').empty();
        $('#noAcadYearsNote').html(`<p class="text-sm text-slate-700 font-sans">No <p class="text-sm text-slate-700 font-sans">No Result</p>`)
    }else{
        acadYears.forEach(acadYear => {
            acadYearstbl_data+= `<tr>
                                <td>${acadYear.ayStarting}-${acadYear.ayEnding}</td>
                                <td>${acadYear.Semester}</td>
                                <td  class="text-center"><a class="cursor-pointer" onclick="editAy(${acadYear.id});
                                    openModalForm('ManageAcadYear')"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            </tr>`
        })

        $('#AcadYears').html(acadYearstbl_data);
        $('#noAcadYearsNote').empty();
    }

}

function manageAcadYearReset(){
    $('#ManageAcadYearForm').trigger('reset');
    $('#ManageAcadYearForm input[name="action_type"]').val('add');
    $('#ManageAcadYearForm input[name="ay_ID"]').val('');
    $('#program_course').html(`<option disabled selected>Select program first</option>`);
    $('#progYrSecSubmit').html(`Add`);
    $('#ay_openProgCourse').empty();
    $('#nocourseNote').html(`<p class="text-sm text-slate-700 font-sans">No selected program course</p>`)
    document.querySelectorAll('.dynamic-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    $('#Ay_availableCourse').val('');

}

async function CurracadYearsOption(){
    let { data: acadYears } = await $.ajax({
        url: '../ajax.php?action=AcadYears',
        method: 'GET',
        dataType: 'json'
    });
    if (acadYears.length === 0){
        $('#CurracademicYear').html(`<option disabled selected>No academic year option</option>`);
    }else{
        let displayedSem = {First: '1st',
            Second: '2nd',
        Midyear: 'Midyear'}
        let acadYearsOptions = acadYears.map(acadYear => {

            return `<option ${acadYear.Curray_sem === 'Yes' ? 'selected' : ''} value="${acadYear.id}">
${displayedSem[acadYear.Semester]} Semester, AY ${acadYear.ayStarting}-${acadYear.ayEnding}
            </option>`;
        }).join('');

        $('#CurracademicYear').html(acadYearsOptions);


    }
}
render_ProgOptions();
CurracadYearsOption()
yrSecCheckboxesOptions()
displayAcadYears();