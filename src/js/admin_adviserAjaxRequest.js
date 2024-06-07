
document.addEventListener('submit', function(e) {
    e.preventDefault();
    let modal,formData,endpoint,loader_id,btn, notification;
    let getNewData = [];
     if (e.target.id === 'narrativeReportsForm'){
        endpoint = 'newFinalReport'
        modal =  'newNarrative';
        loader_id = 'loader_narrative';
        btn = 'newNarrativeSubmitbtn';
        notification = 'SuccessLUploadNotif'
         getNewData.push(dashboard_student_NarrativeReports);
    }if (e.target.id === 'EditNarrativeReportsForm'){
         if (e.submitter.id === 'update_btn'){
             endpoint = 'UpdateNarrativeReport';
             notification = 'SuccessNarrativeEdit'
         }
         else if (e.submitter.id === 'archive_btn'){
             endpoint = 'ArchiveNarrativeReport';
             notification = 'archiveNarrativeNotif'
         }
        getNewData.push(dashboard_student_NarrativeReports);

        loader_id = 'loader_narrative_update';
         btn = 'editNarrativeBtn';
         modal = 'EditNarrative';
    }if (e.target.id === 'studentForm'){
         if (e.submitter.id === 'stud_Submit') {
             endpoint = "newUser";
         }
         notification = 'NewStudentNotif';
        modal = 'newStudentdialog';
        btn = 'newStudBtn';
        loader_id = 'newStudentLoader';
        getNewData.push(get_studenUsertList);

    }if (e.target.id === 'EditStudentForm'){
         if (e.submitter.id === 'update_stud_btn'){
             endpoint = 'updateUserInfo';
         }
        modal = 'editStuInfo';
         btn = 'editStudBtn';
         loader_id = 'editStudentLoader'
        getNewData.push(get_studenUsertList);
        notification = 'EditStudentNotif';

    }
     if (e.target.id === 'admin_adv_Form'){
         endpoint = 'newUser';
         let password = e.target.querySelector('input[name="user_password"]').value;
         let confirmPassword = e.target.querySelector('input[name="user_confPass"]').value;
         if (password !== confirmPassword) {
             openModalForm('passNotmatchNotif')
             return false;
         }
         notification = 'NewadvNotif';
         loader_id ='new_adv_adminLoader'
         btn = 'new_adv_adminBtn';
         modal = 'newAdvierDialog';
         getNewData.push(get_AdvUsertList);

     }
     if (e.target.id === 'EditAdviserForm'){
         endpoint = 'updateUserInfo';
         loader_id = 'editAdVLoader';
         btn = 'editStudBtn'
         modal = 'editAdv_admin';
         getNewData.push(get_AdvUsertList);
         notification = 'EditAdvNotif';

     }
     if (e.target.id === 'NotesForm'){
         endpoint = 'Notes';
         loader_id = 'notes';
         btn = 'noteSubmit';
         modal = 'Notes';
         if (e.target.querySelector('input[name="actionType"]').value === 'edit') {
             notification = 'UpdateNoteNotif';
         } else {
             notification = 'NewNoteNotif';
         }

         getNewData.push(get_dashBoardnotes);
     }
    if (e.target.id === 'act_n_schedForm'){
        endpoint = 'NewActivity';
        loader_id = 'SchedAndActLoader';
        btn = 'SchedAndActbtn';
        modal = 'Act&shedModal';
        if (e.target.querySelector('input[name="actionType"]').value === 'edit') {
            notification = 'UpdateaActSchedNotif';
        } else {
            notification = 'NewActSchedNotif';
        }
        getNewData.push(getActivitiesAndSched);
    }if (e.target.id === 'sectionProgramForm'){
        endpoint = "ProgYrSec"
        loader_id = 'progyrsecLoader';
        btn = 'progyrsecLoaderbtn';
        modal = 'ProgSecFormModal';
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
            if (parseInt(response) === 1) {
                enable_button(btn)
                remove_loader(loader_id);
                closeModalForm(modal);
                openModalForm(notification);
                getNewData.forEach(func => func());
            } else {
                alert(response);
                remove_loader(loader_id);
                enable_button(btn);
            }
        },
    });
});

function isEmpty(variable) {
    return variable === null ||
        variable === undefined ||
        variable === '' ||
        (Array.isArray(variable) && variable.length === 0);
}
