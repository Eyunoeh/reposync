


async function getActivitiesAndSched() {
    try {
        const response = await $.ajax({
            url: '../ajax.php?action=getDashboardActSched',
            method: 'GET',
            dataType: 'html'
        });
        $('#actAndschedList').html(response);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}




function getAdvNotes(){
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
                <div class="flex justify-start gap-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-slate-700 font-bold">Total OJT Hours</span>
                        </div>
                        <input type="number" min="1" required name="ojt_hours" class="bg-slate-100 input input-bordered w-full" placeholder="Type here">
                    </label>
                </div>
            </div>
        </div>
    `);
}

async function getYrSec(){
    let { response, data: yr_secs } = await $.ajax({
        url: '../ajax.php?action=getYrSecJson',
        method: 'GET',
        dataType: 'json'
    });

    let yr_sectbl = '';
    if (response === 1 && yr_secs.length > 0) {
        for (let i = 0; i < yr_secs.length; i++) {
            yr_sectbl += `<tr class="hover">
                    <td>${yr_secs[i]['year']}${yr_secs[i]['section']}</td>
                    <td class="text-center cursor-pointer">   
                     <a onclick="openModalForm('ProgSecFormModal'); EditYrSec( ${yr_secs[i]['year_sec_Id']})">
                     <i class="fa-solid fa-pen-to-square"></i>
                    </a></td>
                </tr>`;
        }
        $('#yrSec').html(yr_sectbl);
    }

}
async function EditYrSec(Id){
    let { response, data: yr_secs } = await $.ajax({
        url: '../ajax.php?action=getYrSecJson',
        method: 'GET',
        dataType: 'json'
    });

    if (response === 1 && yr_secs.length > 0) {
        renderAddYearSec();
        for (let i = 0; i < yr_secs.length; i++) {
            if (yr_secs[i]['year_sec_Id'] === Id){

                $('#progYrSecSubmit').html("Submit")
                $('#sectionProgramForm input[name="action_type"]').val('edit');
                $('#sectionProgramForm input[name="ID"]').val(yr_secs[i]['year_sec_Id'] );
                $('#sectionProgramForm input[name="year"]').val(yr_secs[i]['year']);
                $('#sectionProgramForm input[name="section"]').val(yr_secs[i]['section']);
                return;
            }
        }
    }
}


async function getPrograms(){
    let { response, data: programs } = await $.ajax({
        url: '../ajax.php?action=getProgJSON',
        method: 'GET',
        dataType: 'json'
    });


    let programs_tbl = '';
    if (response === 1 && programs.length > 0) {
        for (let i = 0; i < programs.length; i++) {
            programs_tbl += `<tr class="hover">
                <td>${programs[i]['program_code']}</td>
                <td>${programs[i]['program_name']}</td>
                <td>${programs[i]['ojt_hours']}</td>
                <td class="text-center cursor-pointer">
                    <a onclick="openModalForm('ProgSecFormModal'); EditProgram(${programs[i]['program_id']})">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>`;
        }
        $('#programs').html(programs_tbl);
    }

}
async function EditProgram(Id){
    let { response, data: programs } = await $.ajax({
        url: '../ajax.php?action=getProgJSON',
        method: 'GET',
        dataType: 'json'
    });

    if (response === 1 && programs.length > 0) {
        renderAddProgramInputs();
        for (let i = 0; i < programs.length; i++) {
            if (programs[i]['program_id'] === Id){

                $('#progYrSecSubmit').html("Submit")
                $('#sectionProgramForm input[name="action_type"]').val('edit');
                $('#sectionProgramForm input[name="ID"]').val(programs[i]['program_id']);
                $('#sectionProgramForm input[name="ProgramCode"]').val(programs[i]['program_code']);
                $('#sectionProgramForm input[name="ProgramName"]').val(programs[i]['program_name']);
                $('#sectionProgramForm input[name="ojt_hours"]').val(programs[i]['ojt_hours']);
                return;
            }
        }
    }
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









