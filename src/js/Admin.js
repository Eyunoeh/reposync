get_AdvUsertList();
getActivitiesAndSched();
getPrograms();
getYrSec();
getAdvNotes();
getPendingFinalReports();

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
}function getAdvNotes(){
    $.ajax({
        url: '../ajax.php?action=getAdvNotes',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#NotesReq').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function getPendingFinalReports(){
    $.ajax({
        url: '../ajax.php?action=getPendingFinalReports',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#narrativeReportsReqTableBody').html(response);
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
                $('#EditAdviserForm input[name="user_Mname"]').val(data.middle_name);
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
function EditProgram(Id){
    $.ajax({
        url: '../ajax.php?action=getProgJSON&data_id=' + Id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                renderAddProgramInputs();
                $('#progYrSecSubmit').html("Submit")
                $('#sectionProgramForm input[name="action_type"]').val('edit');
                $('#sectionProgramForm input[name="ID"]').val(data.program_id);
                $('#sectionProgramForm input[name="ProgramCode"]').val(data.program_code);
                $('#sectionProgramForm input[name="ProgramName"]').val(data.program_name);

            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
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
function EditYrSec(Id){
    $.ajax({
        url: '../ajax.php?action=getYrSecJSON&data_id=' + Id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                renderAddYearSec();
                $('#progYrSecSubmit').html("Submit")
                $('#sectionProgramForm input[name="action_type"]').val('edit');
                $('#sectionProgramForm input[name="ID"]').val(data.section_id );
                $('#sectionProgramForm input[name="year"]').val(data.year);
                $('#sectionProgramForm input[name="section"]').val(data.section);


            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
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
    $('#sectionProgramForm input[name="action_type"]').val('');
    $('#sectionProgramForm input[name="ID"]').val('');
    document.getElementById('sectionProgramForm').reset();
    $('#progYrSecSubmit').html("Add")



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
                        $('#status_Box').append('<p class="text-slate-700  text-xs pl-2" id="declineReason">' +
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

function getAdvReqNotesInfo(id){
    $.ajax({
        url: '../ajax.php?action=announcementJson&data_id=' + encodeURIComponent(id),
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                $('#reasonTextArea').empty();
                $('#emailCheckbox').empty();
                if (data.status === 'Declined'){
                    $('#reasonTextArea').html('<label class="form-control w-full ">\n' +
                        '                            <div class="label">\n' +
                        '                                <span class="label-text text-slate-700 font-bold">Reason</span>\n' +
                        '                            </div>\n' +
                        '                            <input type="text" value="'+ data.reason +'" required  name="reason" class="input input-error w-full" placeholder="Type here">' +
                        '                        </label>')

                }
                if (data.status === 'Active' || data.status === 'Declined'){
                    $('#NoteStatReqOptions'). html('<option value="Active">Approve</option>\n' +
                        '                            <option value="Declined">Declined</option>');
                    $('#emailCheckbox').html('<input type="checkbox"  name="emailNotif" value="Notify" checked class="checkbox checkbox-xs mr-2 checkbox-info" />\n' +
                        '                        <span class="label-text text-sm"> Notify OJT adviser through Email?</span>');
                }else{
                    $('#NoteStatReqOptions'). html('<option value="Pending">Pending</option>' +
                        '                            <option value="Active">Approve</option>\n' +
                        '                            <option value="Declined">Declined</option>');
                }
                $('#AdvNoteReqForm select[name="NoteStat"]').val(data.status);
                $('#AdvNoteReqForm input[name="announcementID"]').val(data.announcement_id);
                $('#Notetitle').html(data.title);
                $('#noteMessage').html(data.description);


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
                $('#act_n_schedForm select[name="announcementTarget"]').val(data.SchedAct_targetViewer);
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






function get_AdvUsertList (){
    $.ajax({
        url: '../ajax.php?action=getAdvisers',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.response === 1){
                let data = response.data;
                console.log(data)
                let table_data = 'No result';
                if (data.length > 0){
                    for (let i = 0 ; i < data.length; i++){
                        table_data += `<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i]['first_name']} ${data[i]['last_name']} </span>
                        </td>
                        <td class="p-3 text-center">
                            <span class="font-semibold text-light-inverse text-md/normal">BSBM MM</span>
                            <hr>
                            <span class="font-semibold text-light-inverse text-md/normal">BSBM MM </span>
                        </td>
                        <td class="p-3 text-center">
                            <span class="font-semibold text-light-inverse text-md/normal">4A</span>
                            <hr>
                            <span class="font-semibold text-light-inverse text-md/normal">4B</span>
                        </td>
                        
             
                        <td class="p-3 text-center">
                            <span class="font-semibold text-light-inverse text-md/normal">10</span>
                            <hr>
                            <span class="font-semibold text-light-inverse text-md/normal">10</span>
                        </td>
                        <td class="p-3 text-end">
                            <a onclick="" data-id="'.$row['user_id'].'" href="#" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>`
                    }
                }
                $('#advList').html(table_data)

            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}




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
if (document.getElementById('AdvNoteReqForm')){
    let advNoteStat = document.getElementById('NoteStatReqOptions');
    if (advNoteStat){
        advNoteStat.addEventListener("change", function (){
            if (advNoteStat.value === 'Declined'){
                $('#reasonTextArea').html('<label class="form-control w-full ">\n' +
                    '                            <div class="label">\n' +
                    '                                <span class="label-text text-slate-700 font-bold">Reason</span> \n' +
                    '                            </div>\n' +
                    '                            <input type="text" required  name="reason" class="input input-error w-full" placeholder="Type here">' +
                    '                        </label>')
                $('#emailCheckbox').html('<input type="checkbox"  name="emailNotif" value="Notify" checked class="checkbox checkbox-xs mr-2 checkbox-info" />\n' +
                    '                        <span class="label-text text-sm"> Notify OJT adviser through Email?</span>')
            }else if (advNoteStat.value === 'Active'){
                $('#emailCheckbox').html('<input type="checkbox"  name="emailNotif" value="Notify" checked class="checkbox checkbox-xs mr-2 checkbox-info" />\n' +
                    '                        <span class="label-text text-sm">Notify advisory students of this adviser through Email?</span>')
                $('#reasonTextArea').empty();

            }
            else{
                $('#reasonTextArea').empty();
                $('#emailCheckbox').empty();
            }
        })
    }
}
if (document.getElementById('EditNarrativeReportsReqForm')){
    let uploadstat = document.getElementById('UploadStat');
    if (uploadstat){
        uploadstat.addEventListener("change", function (){
            if (uploadstat.value === 'Declined'){
                $('#declineUploadReason').append('<label class="form-control w-full ">\n' +
                    '                            <div class="label">\n' +
                    '                                <span class="label-text text-slate-700 font-bold">Remarks</span>\n' +
                    '                            </div>\n' +
                    '                            <input type="text" required  name="remark" class="input input-error w-full" placeholder="Type here">' +
                    '                        </label>')
            }
            else{
                $('#declineUploadReason').empty();
            }
        })
    }
}









