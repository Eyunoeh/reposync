
document.addEventListener('submit', function(e) {
    e.preventDefault();
    let modal,formData,endpoint,loader_id,btn

     if (e.target.id === 'narrativeReportsForm'){
        endpoint = 'newFinalReport'
        modal =  'newNarrative';
        loader_id = 'loader_narrative';
        btn = 'newNarrativeSubmitbtn'
    }if (e.target.id === 'EditNarrativeReportsForm'){
         endpoint = 'UpdateNarrativeReport';
         loader_id = 'loader_narrative_update';
         btn = 'editNarrativeBtn';
         modal = 'EditNarrative';
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
            if (response == 1) {
                enable_button(btn)
                remove_loader(loader_id);
                closeModalForm(modal);
                dashboard_student_NarrativeReports();
            } else {
                console.log(response);
            }
            e.target.reset();
        },
    });
});