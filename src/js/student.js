document.addEventListener('submit', function(e) {
   e.preventDefault();
   let modal,formData,endpoint,alertType,alertMessage,alertContainer,loader_id,submit_btn;
   alertContainer = 'notifBox';
   let loadData = [];

   if (e.target.id === 'addWeeklyReportForm'){

      endpoint = 'addWeeklyReport'
      modal = 'newReport';
      alertType = 'success';
      alertMessage = 'Weekly journal has been submitted';
      loadData.push(get_WeeklyReports)
      loadData.push(getUploadLogs)
   }else if (e.target.id === 'resubmitReportForm'){
      endpoint = 'resubmitReport'
      modal = 'resubmitReport';
      alertType = 'success';
      alertMessage = 'Weekly journal has been resubmitted';
      loadData.push(get_WeeklyReports)
      loadData.push(getUploadLogs)
   }
   else if (e.target.id === 'NarrativeReportForm'){
      endpoint = 'newFinalReport'
      alertType = 'success';
      alertMessage = 'New narrative report has been submitted! Please wait for adviser approval';
      modal = 'NarrativeReportmodalForm'

   }else if (e.target.id === 'StudprofileForm'){
      endpoint = 'profileUpdate'
      alertType = 'success';
      alertMessage = 'Profile Information has been updated';
      loadData.push(getProfileInfo)

   }else if (e.target.id === 'StudAccountInfo'){
      endpoint = 'updateAcc'
      alertType = 'success';
      alertMessage = 'Account Information has been updated';

   }
   formData = new FormData(e.target);


   $.ajax({
      url: '../ajax.php?action='+ endpoint,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
         if (response.response === 1) {
            Alert(alertContainer, alertMessage, alertType);
            if (modal){
               closeModalForm(modal)
            }

            if (loadData.length > 0){
               loadData.forEach(func => func());
            }

         } else {
            Alert(alertContainer,response.message, 'warning')
         }
         console.log(response)
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
      getUploadLogs();


   } else {
      weekly_report_tbl.classList.remove('hidden');
      logs_tbl.classList.add('hidden')
      report_view_btn.innerHTML = 'View logs';
      get_WeeklyReports();
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


function changeProfileSettingForm(){
   let accountSettingbtN=  document.getElementById('accountSettingbtN');
   let profile = document.getElementById('StudprofileForm')
   let accountInfo = document.getElementById('StudAccountInfo')
   if (accountSettingbtN.innerHTML === 'Profile Information'){
      accountSettingbtN.innerHTML ='Account Information'

      profile.classList.add('hidden')
      accountInfo.classList.remove('hidden');


   }else {
      profile.classList.remove('hidden')
      accountInfo.classList.add('hidden');
      accountSettingbtN.innerHTML ='Profile Information'
   }
}
async function getProfileInfo() {

      try {

         let response = await user_info();

         if (response.response === 1) {
            let data = response.data;
            let profPath;

            if (data.profile_img_file === 'N/A') {
               profPath = 'assets/profile.jpg';
            } else {
               profPath = 'userProfile/' + data.profile_img_file;
            }
            console.log()

            $('#side_tabName').html(data.first_name + ' ' + data.last_name  + ' - ' + data.user_type.toUpperCase());
            $("#selectedProfile").attr("src", profPath);
            $("#profile_nav").attr("src", profPath);
            $('#StudprofileForm input[name="user_Fname"]').val(data.first_name);
            $('#StudprofileForm input[name="user_Mname"]').val(data.middle_name);
            $('#StudprofileForm input[name="user_Lname"]').val(data.last_name);
            $('#StudprofileForm input[name="user_address"]').val(data.address);
            $('#StudprofileForm input[name="contactNumber"]').val(data.contact_number);
            $('#StudprofileForm input[name="stud_OJT_center"]').val(data.ojt_center);
            $('#StudprofileForm input[name="stud_ojtLocation"]').val(data.ojt_location);

            // Handle sex radio buttons
            if (data.sex === "male") {
               $('#StudprofileForm select[name="user_Sex"]').val('male');
            } else if (data.sex === "female") {
               $('#StudprofileForm select[name="user_Sex"]').val('female');
            }

         } else {
            console.error('Error: Unexpected response format or no data');
         }
      } catch (error) {
         console.error('Error:', error);
      }


}

function resubmitWeeklyReport(weeklyReport_id){
   document.querySelector('#resubmitReport input[name="file_id"]').value = weeklyReport_id;
}

async function getUploadLogs() {
   try {
      const response = await $.ajax({
         url: '../ajax.php?action=getUploadLogs',
         method: 'GET',
         dataType: 'html'
      });

      $('#logsTable_body').html(response);
   } catch (error) {
      console.error('Error fetching data:', error);
   }
}

async function getComments(file_id) {
   try {
      const response = await $.ajax({
         url: '../ajax.php?action=getCommentst&file_id=' + file_id,
         method: 'GET',
         dataType: 'html'
      });

      if (response) {
         $('#comment_body').html(response);
         $('#chatBox input[name="file_id"]').val(file_id);
         scrollToBottom();
      }
   } catch (error) {
      console.error('Error fetching data:', error);
   }
}

function viewImage(srcPath){
   let path = 'comments_img/'+ srcPath;
   $('#viewImage').attr('src', path);
}
function scrollToBottom() {
   let commentBody = document.getElementById('comment_body');
   commentBody.scrollTop = commentBody.scrollHeight;
}

function home_student_NarrativeReports() {
   $.ajax({
      url: '../ajax.php?action=get_narrativeReports&homeTable=request',
      method: 'GET',
      dataType: 'html',
      success: function(response) {
         $('#narrativeReportsTableBody').html(response);
      },
      error: function(xhr, status, error) {

         console.error('Error fetching data:', error);
      }
   });
}

async function get_WeeklyReports() {
   try {
      const response = await $.ajax({
         url: '../ajax.php?action=getWeeklyReports',
         method: 'GET',
         dataType: 'html'
      });


      $('#Weeklyreports').html(response);
   } catch (error) {
      console.error('Error fetching data:', error);
   }
}

async function getSubmittedNarratives(){
   const response = await $.ajax({
      url: '../ajax.php?action=StudsubmittedNarratives',
      method: 'GET',
      dataType: 'json'
   });
   let narratives = response.data
   let data_length = narratives.length;
   let number = 1
   let table_data = ''

   if (data_length === 0) {
      $('#studuploadedNarrativesTableBody').html(`<tr><td colspan="9">No Active / Assigned students found for this adviser.</td></tr>`);
   }
   else {
      narratives.forEach(narrative => {
         let years = narrative.ac_submitted.split(',');
         let startingAC = years[0].trim();
         let endingAC =  years[1].trim();


         table_data += `<tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${number}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">First</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${startingAC} - ${endingAC}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${narrative.file_status}
                            `
                        if (narrative.file_status === 'Declined'){
                           table_data += `<br>Reason: <span class="text-warning text-sm">
                                                   ${narrative.remarks}</span>`
                        }
                         table_data += `
                                        </span>
                                    </td>
            
                                    <td class="p-3 text-end">
                                    
                                        `
                                       if (['Converted', 'Archived'].includes(narrative.file_status)){

                                          table_data +=  `<a href="flipbook.php?view=${narrative.narrative_id}" class="font-semibold cursor-pointer text-light-inverse text-md/normal break-words">
                                                            <i class="fa-regular fa-eye"></i></a>`
                                       }else {
                                          table_data += ` <a class="font-semibold cursor-pointer text-light-inverse text-md/normal break-words">
                                                            <i class="fa-solid fa-circle-info"></i></a>`
                                       }
                                      table_data += `
                                       
                                           
                                    </td>
                                </tr>`;
         number++

      })
      $('#studuploadedNarrativesTableBody').html(table_data);
   }


}

