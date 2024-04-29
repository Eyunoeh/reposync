document.addEventListener('submit', function(e) {
   e.preventDefault();
   let modal,formData,endpoint,loader_id,submit_btn

   if (e.target.id === 'addWeeklyReportForm'){
      endpoint = 'addWeeklyReport'
   }else if (e.target.id === 'resubmitReportForm'){
      endpoint = 'resubmitReport'
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


function change_stud_table () {
   let report_view_btn = document.getElementById('stud-weekly-rpt-btn');
   let weekly_report_tbl = document.getElementById('weeklyReportTable');
   let logs_tbl = document.getElementById('logsTable');

   if (report_view_btn.innerHTML === 'View logs') {
      report_view_btn.innerHTML = 'View report';
      weekly_report_tbl.classList.add('hidden') ;
      logs_tbl.classList.remove('hidden')

   } else {
      weekly_report_tbl.classList.remove('hidden');
      logs_tbl.classList.add('hidden')
      report_view_btn.innerHTML = 'View logs';
   }
}
function getVisibleTableId() {
   let logs_tbl = document.getElementById('logsTable');
   if (!logs_tbl.classList.contains('hidden')) {
      return 'logsTable';
   } else {
      return 'weeklyReportTable';
   }
}