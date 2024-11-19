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

$('#addCourseBtn').on('click', function(e) {
    const container = document.getElementById('yrSecOptions');
    const checkedValues = Array.from(container.querySelectorAll('.dynamic-checkbox:checked'))
        .map(checkbox => checkbox.value);
    console.log(checkedValues); // Outputs the values of checked checkboxes
});

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