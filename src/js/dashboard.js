/*function handleRouting() {
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

 */


document.addEventListener('DOMContentLoaded', function (){
    navigate('dashboardContent.php')
})
function navigate(page) {
    fetch(page, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('dashboard_main_content').innerHTML = html;
            get_studenUsertList();
            get_AdvUsertList();
            get_dashBoardnotes();
            getActivitiesAndSched();
            getPrograms();
            getYrSec();
            if (document.getElementById('act_n_schedForm')) {
                const startDateInput = document.querySelector('input[name="startDate"]');
                const endDateInput = document.querySelector('input[name="endDate"]');
                startDateInput.addEventListener('input', function() {
                    if (endDateInput.value && endDateInput.value < startDateInput.value) {
                        endDateInput.value = '';
                    }
                });
                endDateInput.addEventListener('input', function() {
                    if (startDateInput.value && startDateInput.value > endDateInput.value) {
                        startDateInput.value = '';
                    }
                });
            }


            if (document.getElementById('NewNote')){
                document.getElementById('NewNote').addEventListener('click', function (){
                    document.getElementById('action_type').value = ''
                    document.getElementById('announcementID').value = '';
                    document.getElementById('NotesForm').reset();

                });
            }
            if (document.getElementById('act_n_schedForm')){
                document.getElementById('newAct').addEventListener('click', function (){
                    document.getElementById('action_type').value = ''
                    document.getElementById('announcementID').value = '';
                    document.getElementById('act_n_schedForm').reset();

                })
            }
            if (document.getElementById('SectionProgramFormInputs')){
                if (document.getElementById('SectionProgramFormInputs')) {
                    attachSelectEventListener();
                }
            }

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
    }else if (tab.id === 'notesReq'){
        navigate('notesReqmanage.php')
    } else if (tab.id === 'schedule&Act'){
        navigate('manamgeAct&Sched.php')
    } else if (tab.id === 'dashBoardWeeklyReport'){
        navigate('manageWeeklyReport.php')
    }else if (tab.id === 'stud_list'){
        navigate('manageStudent.php')
    }else if (tab.id === 'adv_list'){
        navigate('manageAdvisers.php')
    }else if (tab.id === 'dashBoardProg_n_Section'){
        navigate('ManageProgSec.php');
    }
    act_tab(tab.id);
    if(tab.id === 'dshbContentLinkActStud'){
        navigate('manageStudent.php');
        act_tab('stud_list');
    }else if(tab.id === 'dshbContentLinkActAdv'){
        act_tab('adv_list');
        navigate('manageAdvisers.php')
    }else if(tab.id === 'dshbContentLinkNarratives'){
        act_tab('dashboard_narrative');
        navigate('manageNarrativeReports.php')
    }

}
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
function get_dashBoardnotes (){
    $.ajax({
        url: '../ajax.php?action=getDashboardNotes',
        method: 'GET',
        dataType: 'html',
        success: function(response) {

            $('#AdviserNotes').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function getActivitiesAndSched (){
    $.ajax({
        url: '../ajax.php?action=getDashboardActSched',
        method: 'GET',
        dataType: 'html',
        success: function(response) {

            $('#actAndschedList').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function getYrSec(){
    $.ajax({
        url: '../ajax.php?action=getDasboardYrSec',
        method: 'GET',
        dataType: 'html',
        success: function(response) {

            $('#yrSec').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function getPrograms(){
    $.ajax({
        url: '../ajax.php?action=getDasboardPrograms',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#programs').html(response);
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
                $('#EditStudentForm input[name="stud_compName"]').val(data.company_name);
                $('#EditStudentForm input[name="stud_TrainingHours"]').val(data.training_hours);
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
function getNotes(note_id){
    $.ajax({
        url: '../ajax.php?action=announcementJson&data_id=' + encodeURIComponent(note_id),
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                let status = data.status;
                let status_class = '';




                $('#status_Box').append('<p class="" id="NoteStat"></p>');
                document.getElementById('NoteStat').className = '';
                $('#status_Box').find('#NoteStat').html(status);
                $('#status_Box').find('#declineReason').remove();
                switch (status) {
                    case 'Declined':
                        $('#status_Box').append('<p class="text-slate-700 font-semibold text-xs pl-2" id="declineReason">' +
                            '<strong>Reason:</strong> ' + data.reason +
                            '</p>');
                        status_class = 'text-error';
                        break;
                    case 'Pending':
                        status_class = 'text-warning';
                        break;
                    case 'Active':
                        status_class = 'text-success';
                        break;
                    default:
                        status_class = ''; // Default class if none of the cases match
                }

                document.getElementById('NoteStat').classList.add('font-semibold', 'text-sm', 'pl-2', status_class);


                $('#NotesForm input[name="noteTitle"]').val(data.title);
                $('#NotesForm textarea[name="message"]').val(data.description);
                $('#NotesForm input[name="announcementID"]').val(data.announcement_id);
                $('#NotesForm input[name="actionType"]').val('edit');
                $('#NoteTitle').append('<div id="trashAnnouncementBtn" class="trash tooltip tooltip-bottom tooltip-error text-sm" data-tip="Delete note">' +
                    '<a  onclick="deleteAnnoucement(this.getAttribute(\'data-id\'),\'Notes\')" data-id="' + data.announcement_id + '" class="btn-sm btn btn-circle btn-ghost hover:cursor-pointer text-error"><i class="fa-solid fa-trash"></i></a>' +
                    '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function getActSched(actId){
    $.ajax({
        url: '../ajax.php?action=announcementJson&data_id=' + encodeURIComponent(actId),
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                $('#act_n_schedForm input[name="Activitytitle"]').val(data.title);
                $('#act_n_schedForm textarea[name="description"]').val(data.description);
                $('#act_n_schedForm input[name="announcementID"]').val(data.announcement_id);
                $('#act_n_schedForm input[name="actionType"]').val('edit');
                $('#act_n_schedForm input[name="startDate"]').val(data.starting_date);
                $('#act_n_schedForm input[name="endDate"]').val(data.end_date);
                $('#act_schedtitle').append('<div id="trashAnnouncementBtn" class="trash tooltip tooltip-bottom tooltip-error text-sm" data-tip="Delete activity">' +
                    '<a  onclick="deleteAnnoucement(this.getAttribute(\'data-id\'),\'Act&shedModal\')" data-id="' + data.announcement_id + '" class="btn-sm btn btn-circle btn-ghost hover:cursor-pointer text-error"><i class="fa-solid fa-trash"></i></a>' +
                    '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function removeTrashButton() {
    $('#trashAnnouncementBtn').remove();
}
function removeStatusBoxContent(){
    $('#status_Box').empty();
}







function deleteAnnoucement(id, modal_id){
    $.ajax({
        url: '../ajax.php?action=deleteAnnouncement&data_id=' + encodeURIComponent(id),
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response){
                if (parseInt(response) === 1){
                    get_dashBoardnotes ();
                    getActivitiesAndSched()
                    closeModalForm(modal_id);
                }
            }
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}


function renderAddProgramInputs() {
    $('#SectionProgramFormInputs').html(`
        <div class="flex flex-col gap-2">
            <div class="flex flex-col gap-2">
                <div class="flex justify-start gap-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700 font-bold">Program Code</span>
                        </div>
                        <input type="text" required name="ProgramCode" placeholder="Type here" class="bg-slate-100 input input-bordered w-full" />
                    </label>
                </div>
                <div class="flex justify-start gap-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700 font-bold">Program Name</span>
                        </div>
                        <input type="text" required name="ProgramName" class="bg-slate-100 input input-bordered w-full" placeholder="Type here">
                    </label>
                </div>
            </div>
        </div>
    `);
}

function renderAddYearSec(){
        $('#SectionProgramFormInputs').html(`
        <div class="flex flex-col gap-2">
            <div class="flex flex-col gap-2">
                <div class="flex justify-start gap-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700 font-bold">Year</span>
                        </div>
                        <input type="number" required name="year" placeholder="Type here" class="bg-slate-100 input input-bordered w-full" />
                    </label>
                </div>
                <div class="flex justify-start gap-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700 font-bold">Section</span>
                        </div>
                        <input type="text" required name="section" class="bg-slate-100 input input-bordered w-full" placeholder="Type here">
                    </label>
                </div>
            </div>
        </div>
    `
        );
}

function renderSelectformOption(){
    $('#option').html(`
        <select id="formSelect" class="select  select-bordered w-full max-w-xs">
            <option value="newProg">Program</option>
            <option value="newyrSec">Year and Section</option>
        </select>
    `
    );
    attachSelectEventListener();
}


function attachSelectEventListener() {
    let formSelect = document.getElementById('formSelect');
    if (formSelect) {
        let selectedValue = 'newProg';
        formSelect.addEventListener('change', function() {
             selectedValue = this.value;
            if (document.getElementById('SectionProgramFormInputs')) {
                if (selectedValue === 'newProg') {
                    renderAddProgramInputs();
                } else if (selectedValue === 'newyrSec') {
                    renderAddYearSec();
                }
            }
        });
    }
}

function removeSelectFormOption(){
    $('#formSelect').remove()
}





