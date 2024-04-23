
document.addEventListener('submit', function(e) {
   e.preventDefault();
   let modal
   let formData
   let endpoint
   let loader_id
   let submit_btn
   if (e.target.id === 'addWeeklyReportForm'){
      endpoint = 'addWeeklyReport'
   }else if (e.target.id === 'resubmitReportForm'){
      endpoint = 'resubmitReport'
   }else if (e.target.id === 'narrativeReportsForm'){
      endpoint = 'newFinalReport'
      modal =  'newNarrative';
      loader_id = 'loader_narrative';
      submit_btn = 'submit_btn'
   }
   formData = new FormData(e.target);
   add_loader(loader_id);
   disable_button(submit_btn)

   $.ajax({
      url: '../ajax.php?action='+ endpoint,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
         if (response == 1) {
            enable_button(submit_btn)
            remove_loader(loader_id);
            closeModalForm(modal);
         } else {
            console.log(response);
         }
         e.target.reset();
      },
   });
});
