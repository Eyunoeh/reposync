async function getActivitiesAndSched() {
    try {
        const response = await $.ajax({
            url: '../ajax.php?action=getDashboardActSched',
            method: 'GET',
            dataType: 'json'
        });
        let actAndschedList = '';
        if (response.response === 1){
            let actScheds = response.data;
            actScheds.forEach(actSched =>{
                actAndschedList += `<div onclick="removeTrashButton();openModalForm('Act&shedModal');
            getActSched(${actSched['announcement_id']})"
             class="text-sm text-slate-700 sm:text-base flex transform max-w-[50rem] w-full transition duration-500 shadow rounded
            hover:scale-110 hover:bg-slate-300  justify-start items-center cursor-pointer">
            <div class=" min-w-[12rem]  p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">`
                if (actSched['starting_date'] === actSched['end_date']){
                    actAndschedList += `<h4 class="text-start">${actSched['starting_date']}</h4>`
                }else {
                    actAndschedList += `<h4 class="text-start">${actSched['starting_date']}</h4>`
                    actAndschedList += `<h4 class="text-start">${actSched['end_date']}</h4>`
                }
                actAndschedList += `</div>
          <div class="flex flex-col justify-center  p-3">
                <h1 class="font-semibold  break-words">${actSched['title']}</h1>
                
                     <p class="text-justify text-sm pr-5 break-words">
        ${actSched['description'].replace(/\r\n|\r|\n/g, '<br>')}
                </p>
    
                
            </div>
        </div>`
            })
            $('#actAndschedList').html(actAndschedList);
        }else {
            $('#actAndschedList').html(`<div class="flex transform w-[50rem]    justify-center items-center ">
            <h1 class="font-semibold">No activity and schedule posted</h1>
        </div>`);
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}




async function getAdvNotes() {
    try {
        const response = await $.ajax({
            url: '../ajax.php?action=getAdvNotes',
            method: 'GET',
            dataType: 'json'
        });
        let  NotesReq = '';
        if (response.response === 1){

            let noteReqs = response.data;
            noteReqs.forEach(noteReq =>{
                NotesReq += `<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${noteReq['first_name']} ${noteReq['last_name']}</span>
                        </td>

                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${noteReq['title']}</span>
                        </td>
                        
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${noteReq['status']}</span>
                        </td>
                         <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${formatDateTime(noteReq['announcementPosted'])}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${formatDateTime(noteReq['announcementUpdated'])}</span>
                        </td>
                        <td class="p-3 text-end">
                            <a href="#" class="hover:cursor-pointer mb-1
                            font-semibold transition-colors duration-200
                            ease-in-out text-lg/normal text-secondary-inverse
                            hover:text-accent"><i class="fa-solid fa-circle-info" onclick="openModalForm('AdviserNoteReq');getAdvReqNotesInfo(${noteReq['announcement_id']})"></i></a>
                        </td>
                    </tr>`
            })
            $('#NotesReq').html(NotesReq);
        }else {
            $('#NotesReq').html(`<tr><td colspan="9">No pending adviser notes</td></tr>`
            );
        }



    } catch (error) {
        console.error('Error fetching data:', error);
    }
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
                <div class="flex justify-start gap-2">
              <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text text-slate-700 font-bold">Total Narrative Reports</span>
                                    </div>
                                    <input type="number" min="1" required name="totalNarratives" class="bg-slate-100 input input-bordered w-full" placeholder="Type here">
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
    }else {
        $('#tableNoResYrSec').html('<p class="text-sm text-slate-700 font-sans">No result</p>')
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
                <td class="text-center">${programs[i]['totalNarratives']}</td>
                <td class="text-center cursor-pointer">
                    <a onclick="openModalForm('ProgSecFormModal'); EditProgram(${programs[i]['program_id']})">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>`;
        }
        $('#programs').html(programs_tbl);
    }else {
            $('#tableNoResProg').html(`
                    <p class="text-sm text-slate-700 font-sans">No result</p>`);
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
                $('#sectionProgramForm input[name="totalNarratives"]').val(programs[i]['totalNarratives']);
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







function act_n_schedForm_inp_lstner(){
    const startDateInput = document.querySelector('input[name="startDate"]');
    const endDateInput = document.querySelector('input[name="endDate"]');
    const today = new Date().toISOString().split('T')[0];

    // Set the min attribute to today's date
    startDateInput.setAttribute('min', today);
    endDateInput.setAttribute('min', today);



    startDateInput.addEventListener('input', function() {
        if (endDateInput.value < startDateInput.value) {
            endDateInput.value = '';
        }
        endDateInput.setAttribute('min', startDateInput.value);
    });
    endDateInput.addEventListener('input', function() {
        if (startDateInput.value > endDateInput.value) {
            startDateInput.value = '';
        }
    });
    document.getElementById('newAct').addEventListener('click', function (){
        document.getElementById('action_type').value = ''
        document.getElementById('announcementID').value = '';
        document.getElementById('act_n_schedForm').reset();

    })
}




function advNoteReqEventListener(){
    let advNoteStat = document.getElementById('NoteStatReqOptions');
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


async function renderTotalArchive(){
    let total_ArchivedNarrative = await getTotalPublihed(4)
    let totalArchiveStud = parseInt(await totalUser(2, 3), 10);
    let totalAchiveAdv = parseInt(await totalUser(2, 2), 10);
    let totalArchiveUsers = totalArchiveStud + totalAchiveAdv;



    $('#totalArchiveNarrative').html(total_ArchivedNarrative)
    $('#total_archiveUsers').html(totalArchiveUsers)
}









