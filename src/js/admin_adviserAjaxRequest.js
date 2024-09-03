
document.addEventListener('submit', function(e) {
    e.preventDefault();
    let modal,formData,endpoint,loader_id,btn, notification, notifType, notifMessaage;
    let  alertContainer = 'notifBox';
    let getNewData = [];

    if (e.target.id === 'studentForm'){
         if (e.submitter.id === 'stud_Submit') {
             endpoint = "newUser";
         }
        notifType = 'success'
        modal = 'newStudentdialog';
        btn = 'newStudBtn';
        loader_id = 'newStudentLoader';
        getNewData.push(get_studenUsertList);

    }if (e.target.id === 'EditStudentForm'){
         if (e.submitter.id === 'update_stud_btn'){
             endpoint = 'updateUserInfo';
         }
        notifType = 'info'
        modal = 'editStuInfo';
         btn = 'editStudBtn';
         loader_id = 'editStudentLoader'
        getNewData.push(get_studenUsertList);


    }
     if (e.target.id === 'admin_adv_Form'){
         endpoint = 'newUser';
         let password = e.target.querySelector('input[name="user_password"]').value;
         let confirmPassword = e.target.querySelector('input[name="user_confPass"]').value;
         if (password !== confirmPassword) {
             openModalForm('passNotmatchNotif')
             return false;
         }

         loader_id ='new_adv_adminLoader'
         btn = 'new_adv_adminBtn';
         modal = 'newAdvierDialog';
         notifType = 'success'
         getNewData.push(get_AdvUsertList);

     }
     if (e.target.id === 'EditAdviserForm'){
         endpoint = 'updateUserInfo';
         loader_id = 'editAdVLoader';
         btn = 'editStudBtn'
         modal = 'editAdv_admin';
         notifType = 'info'
         getNewData.push(get_AdvUsertList);


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
                $('#ProgrYrSecNotifText').html('New program has been added!')

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
    if (e.target.id === 'EditNarrativeReportsReqForm'){
        endpoint = 'UpdateNarrativeReport';
        loader_id = 'loader_narrative_update';
        modal = 'EditNarrativeReq';
        btn = 'editNarrativeBtn';
        notification = 'SuccessNarrativeEdit';
        getNewData.push(getPendingFinalReports);
    }
    if (e.target.id === 'narrativeReportsForm'){
        endpoint = 'newFinalReport'
        modal =  'newNarrative';
        loader_id = 'loader_narrative';
        btn = 'newNarrativeSubmitbtn';
        notification = 'SuccessLUploadNotif'
        getNewData.push(getPendingFinalReports);
    }//adminto end

    if (e.target.id === 'profileForm'){
        endpoint = 'profileUpdate'
        loader_id = 'profileLoader';
        btn = 'profilSbmt';
        notification = 'prfupdateNotif'
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

                Alert(alertContainer, response.message,notifType)
                //openModalForm(notification);
                if (getNewData.length > 0){
                    getNewData.forEach(func => func());

                }
               

            } else {

                remove_loader(loader_id);
                enable_button(btn);
                Alert(alertContainer, response.message,'warning')
                console.log(response.message)
            }
            console.log(response)
        },
    });
});

function isEmpty(variable) {
    return variable === null ||
        variable === undefined ||
        variable === '' ||
        (Array.isArray(variable) && variable.length === 0);
}
