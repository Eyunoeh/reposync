
document.addEventListener('submit', function(e) {
    e.preventDefault();
    let modal,formData,endpoint,loader_id,btn, notification, notifType, notifMessaage;
    let  alertContainer = 'notifBox';
    let getNewData = [];
    let submitterName;

    if (e.target.id === 'studentForm'){
        submitterName = $('#stud_Submit').text()
         if (submitterName === 'Submit') {
             endpoint = "newUser";
         }else if (submitterName === 'Save') {
             endpoint = "updateUserInfo";
         }
        notifType = 'success'
        modal = 'manageStudModalForm';
        btn = 'newStudBtn';
        loader_id = 'newStudentLoader';
        getNewData.push(get_studentUserList);

    }if (e.target.id === 'studentFormxls'){
        endpoint = "ExcelImport";
        notifType = 'success'
        modal = 'manageStudModalFormxls';
        btn = 'newStudBtnxls';
        loader_id = 'newStudentXLXSLoader';
        getNewData.push(get_studentUserList);
    }


     if (e.target.id === 'admin_adv_Form'){

         submitterName = $('#admin_adv_Submit').text();
        if ( submitterName === 'Submit'){
            endpoint = 'newUser'
        }else if (submitterName === 'Save'){
            endpoint =  'updateUserInfo'
        }
         if (e.target.querySelector('input[name="assignedAdvList"]').value === '') {
             Alert('formAlertbox', 'Must select at least 1 handle advisory', 'warning');
             return ;
         }
         loader_id ='new_adv_adminLoader'
         btn = 'new_adv_adminBtn';
         modal = 'newAdvierDialog';
         notifType = 'success'
         getNewData.push(render_AdvUsertList);

     }

     if (e.target.id === 'NotesForm'){
         endpoint = 'Notes';
         loader_id = 'notes';
         btn = 'noteSubmit';
         modal = 'Notes';
         if (e.target.querySelector('input[name="actionType"]').value === 'edit') {
             notifType = 'info';
         } else {
             notifType = 'success';
         }

         getNewData.push(get_dashBoardnotes);
     }
    if (e.target.id === 'act_n_schedForm'){
        endpoint = 'NewActivity';
        loader_id = 'SchedAndActLoader';
        btn = 'SchedAndActbtn';
        modal = 'Act&shedModal';
        if (e.target.querySelector('input[name="actionType"]').value === 'edit') {
            notifType = 'info';
        } else {
            notifType = 'success';
        }
        getNewData.push(getActivitiesAndSched);
    }if (e.target.id === 'sectionProgramForm'){
        endpoint = "ProgYrSec"
        loader_id = 'progyrsecLoader';
        btn = 'progyrsecLoaderbtn';
        modal = 'ProgSecFormModal';
        notification = 'ProgYrSecNotif';
        if (e.target.querySelector('input[name="action_type"]').value === 'edit') {
            $('#ProgrYrSecNotifText').html('Updated!')

        }else {
            if (e.target.querySelector('input[name="ProgramCode"]') &&
                e.target.querySelector('input[name="ProgramName"]')) {
                let prog_course = e.target.querySelector('input[name="ojt_course_json"]').value;

                if (prog_course && Array.isArray(JSON.parse(prog_course)) && JSON.parse(prog_course).length > 0) {
                    $('#ProgrYrSecNotifText').html('New program has been added!');
                } else {
                    Alert('errNotifcotainer', 'Please add a course code.', 'warning');
                    return;
                }

            }else if (e.target.querySelector('input[name="year"]') &&
                e.target.querySelector('input[name="section"]')) {
                $('#ProgrYrSecNotifText').html('Year and Section added!')

            }
        }
        getNewData.push(getYrSec);
        getNewData.push(getPrograms);

    }if (e.target.id === 'AdvNoteReqForm'){
        endpoint = 'UpdateNotePostReq';
        loader_id = 'UpdateNoteLoader';
        modal = 'AdviserNoteReq';
        btn = 'UpdateNoteBtn';
        notification = 'NoteStatUpdateNotif';
        getNewData.push(getAdvNotes);
    }

    //admin to
    if (e.target.id === 'UpdSubNarrativeReport'){
        endpoint = 'UpdStudSubNarrativeReport';
        loader_id = 'loader_narrative_update';
        modal = 'EditNarrativeReq';
        btn = 'editNarrativeBtn';
        notification = 'SuccessNarrativeEdit';
        getNewData.push(getStudSubmittedNarratives);
    }
/*    if (e.target.id === 'narrativeReportsForm'){
        endpoint = 'newFinalReport'
        modal =  'newNarrative';
        loader_id = 'loader_narrative';
        btn = 'newNarrativeSubmitbtn';
        notification = 'SuccessLUploadNotif'
        getNewData.push(getPendingFinalReports);
    }//adminto end*/

    if (e.target.id === 'profileForm'){
        notifType = 'info'
        endpoint = 'profileUpdate'
        loader_id = 'profileLoader';
        btn = 'profilSbmt';

        getNewData.push(getProfileInfo);
    }if (e.target.id === 'Accountform'){
        endpoint = 'updateAcc'
        loader_id = 'accountLoader';
        btn = 'acccountSbmt';
        notification = 'accupdateNotif'
    }

    formData = new FormData(e.target);

    add_loader(loader_id);

    disable_button(btn)

    $.ajax({
        url: '../ajax.php?action='+ endpoint,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (modal){
                closeModalForm(modal);

            }
            if (response.response === 1) {
                enable_button(btn)
                remove_loader(loader_id);
                console.log(response.message)
                Alert(alertContainer, response.message,notifType)
                //openModalForm(notification);
                if (getNewData.length > 0){
                    getNewData.forEach(func => func());

                }
               

            } else {

                remove_loader(loader_id);
                enable_button(btn);
                Alert(alertContainer, response.message,'error')
                console.log(response.message)
            }
            console.log(response)
        },error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
});

function isEmpty(variable) {
    return variable === null ||
        variable === undefined ||
        variable === '' ||
        (Array.isArray(variable) && variable.length === 0);
}
