function handleRouting() {
    const path = window.location.pathname;
    const routes = {
        '/ReposyncNarrativeManagementSystem/src/dashboard.php':'dashboardContent.php',
    };

    if (routes[path]) {
        navigate(routes[path]);
        act_tab('dashboard');
    } else {
        handle404();
    }
}
function navigate(page) {
    fetch(page, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Add a custom header
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('dashboard_main_content').innerHTML = html;
            dashboard_student_NarrativeReports();
            get_studenUsertList();
            get_AdvUsertList();
        })
        .catch(error => console.error('Error fetching content:', error));
}

function handle404() {
    window.location.href = '/404.php';
}
function dashboard_tab(id){
    let tab = document.getElementById(id);
    if(tab.id === 'dashboard_narrative'){
        navigate('manageNarrativeReports.php');
    } else if (tab.id === 'dashboard'){
        navigate('dashboardContent.php');
    } else if (tab.id === 'adviserNotes'){
        navigate('manageAdviserNote.php')
    }else if (tab.id === 'schedule&Act'){
        navigate('manamgeAct&Sched.php')
    } else if (tab.id === 'dashBoardWeeklyReport'){
        navigate('manageWeeklyReport.php')
    }else if (tab.id === 'stud_list'){
        navigate('manageStudent.php')
    }else if (tab.id === 'adv_list'){
        navigate('manageAdvisers.php')
    }
    act_tab(tab.id);
}
window.onload = handleRouting;
function act_tab(id){
    const allTabs = document.querySelectorAll('.dashboard_tab'); // Assuming all tabs have the 'tab' class
    allTabs.forEach(tab => {
        tab.classList.add('text-white');
        tab.classList.remove('text-black', 'bg-gray-300', 'rounded');
    });
    let tab = document.getElementById(id);
    tab.classList.add('text-black', 'bg-gray-300', 'rounded');
    tab.classList.remove('text-white');
}



function dashboard_student_NarrativeReports() {
    $.ajax({
        url: '../ajax.php?action=get_narrativeReports&dashboardTable=request',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#narrativeReportsTableBody').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function get_studenUsertList (){
    $.ajax({
        url: '../ajax.php?action=getStudentsList',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#studentsList').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function get_AdvUsertList (){
    $.ajax({
        url: '../ajax.php?action=getAdvisers',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#advList').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function editNarrative(narrative_id){
    $.ajax({
        url: '../ajax.php?action=narrativeReportsJson&narrative_id=' + narrative_id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                document.querySelector('#EditNarrativeReportsForm input[name="first_name"]').value = data.first_name;
                document.querySelector('#EditNarrativeReportsForm input[name="last_name"]').value = data.last_name;
                document.querySelector('#EditNarrativeReportsForm input[name="school_id"]').value = data.stud_school_id;
                document.querySelector('#EditNarrativeReportsForm select[name="program"]').value = data.program;
                document.querySelector('#EditNarrativeReportsForm input[name="section"]').value = data.section;
                if (data.sex === "Male") {
                    document.querySelector('#EditNarrativeReportsForm input[name="stud_Sex"][value="Male"]').checked = true;
                } else if (data.sex === "Female") {
                    document.querySelector('#EditNarrativeReportsForm input[name="stud_Sex"][value="Female"]').checked = true;
                }

                document.querySelector('#EditNarrativeReportsForm input[name="ojt_adviser"]').value = data.OJT_adviser;
                document.querySelector('#EditNarrativeReportsForm input[name="narrative_id"]').value = data.narrative_id;
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function editUserStud_Info(user_id) {
    $.ajax({
        url: '../ajax.php?action=getStudInfoJson&data_id=' + user_id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                $('#EditStudentForm input[name="user_Fname"]').val(data.first_name);
                $('#EditStudentForm input[name="user_Lname"]').val(data.last_name);
                $('#EditStudentForm input[name="user_address"]').val(data.address);
                $('#EditStudentForm input[name="contactNumber"]').val(data.contact_number);
                $('#EditStudentForm input[name="school_id"]').val(data.school_id);
                $('#EditStudentForm input[name="user_Email"]').val(data.email);
                $('#EditStudentForm input[name="user_id"]').val(data.user_id);
                $('#deactivate_stud_acc').attr('data-user_id', data.user_id);
                $('#EditStudentForm select[name="stud_Program"]').val(data.program_id);
                $('#EditStudentForm select[name="stud_Section"]').val(data.section_id);
                $('#EditStudentForm select[name="stud_adviser"]').val(data.adviser_id);
                if (data.sex === "Male") {
                    $('#EditStudentForm input[name="user_Sex"][value="Male"]').prop('checked', true);
                } else if (data.sex === "Female") {
                    $('#EditStudentForm input[name="user_Sex"][value="Female"]').prop('checked', true);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}


function deactivate_account(id, modal_id){

    $.ajax({
        url: '../ajax.php?action=deactivate_account&data_id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response){
               if (parseInt(response) === 1){
                   closeModalForm(modal_id);
                   get_studenUsertList();
               }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function editAdvInfo(user_id){
    $.ajax({
        url: '../ajax.php?action=getAdvInfoJson&data_id=' + user_id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){

                $('#EditAdviserForm input[name="user_Fname"]').val(data.first_name);
                $('#EditAdviserForm input[name="user_Lname"]').val(data.last_name);
                $('#EditAdviserForm input[name="user_address"]').val(data.address);
                $('#EditAdviserForm input[name="contactNumber"]').val(data.contact_number);
                $('#EditAdviserForm input[name="school_id"]').val(data.school_id);
                $('#EditAdviserForm input[name="user_Email"]').val(data.email);
                $('#EditAdviserForm input[name="user_id"]').val(data.user_id);
                $('#EditAdviserForm select[name="user_type"]').val(data.user_type);
                $('#deactivate_adv').attr('data-user_id', data.user_id);

                if (data.sex === "Male") {
                    $('#EditAdviserForm input[name="user_Sex"][value="Male"]').prop('checked', true);
                } else if (data.sex === "Female") {
                    $('#EditAdviserForm input[name="user_Sex"][value="Female"]').prop('checked', true);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}


