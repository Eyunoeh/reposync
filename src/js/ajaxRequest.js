document.addEventListener('submit', function(e) {
   e.preventDefault();
   let formData
   let endpoint
   if (e.target.id === 'addWeeklyReportForm'){
      endpoint = 'addWeeklyReport'
   }else if (e.target.id === 'resubmitReportForm'){
      endpoint = 'resubmitReport'
   }else if (e.target.id === 'narrativeReportsForm'){
      endpoint = 'newFinalReport'
   }
   formData = new FormData(e.target);
   $.ajax({
      url: '../ajax.php?action='+ endpoint,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
         if (response == 1) {
            console.log(response);

         } else {
            console.log(response);
         }
         e.target.reset();
      },
   });
});
