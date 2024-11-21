document.getElementById('ManageAcadYearForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission
    let endpoint;
    const formData = new FormData(this); // Collect form data
    endpoint = $('#action_type').val() === 'edit' ? 'updateAy' : 'newAy';


    $.ajax({
        url: '../ajax.php?action= '+ endpoint,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Success:', response);

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

    console.log(programs);
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




render_ProgOptions();
yrSecCheckboxesOptions()