document.addEventListener('submit', function(e) {
   e.preventDefault();
   let modal,formData,endpoint,alertType,alertMessage,alertContainer,loader_id,submit_btn;
   alertContainer = 'notifBox';
   let loadData = [];

   if (e.target.id === 'addWeeklyReportForm'){
      endpoint = 'addWeeklyReport'
      modal= 'newReport';
      alertType = 'success';
      alertMessage = 'Weekly report has been submitted';
      loadData.push(get_WeeklyReports)
      loadData.push(getUploadLogs)
   }else if (e.target.id === 'resubmitReportForm'){
      endpoint = 'resubmitReport'
      modal = 'resubmitReport';
      alertType = 'success';
      alertMessage = 'Weekly report has been resubmitted';
      loadData.push(get_WeeklyReports)
      loadData.push(getUploadLogs)
   }
   else if (e.target.id === 'StudprofileForm'){
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
function getProfileInfo(){
   $.ajax({
      url: '../ajax.php?action=get_Profile_info',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
         if (data){
            let profPath
            if (data.profile_img_file === 'N/A'){

               profPath = 'assets/profile.jpg'
            }else {
               profPath = 'userProfile/'+data.profile_img_file
            }
            $("#profile_nav").attr("src", profPath);


            $("#selectedProfile").attr("src", profPath);

            $('#StudprofileForm input[name="user_Fname"]').val(data.first_name);
            $('#StudprofileForm input[name="user_Mname"]').val(data.middle_name);
            $('#StudprofileForm input[name="user_Lname"]').val(data.last_name);
            $('#StudprofileForm input[name="user_address"]').val(data.address);
            $('#StudprofileForm input[name="contactNumber"]').val(data.contact_number);
            $('#StudprofileForm input[name="stud_compName"]').val(data.company_name);
            $('#StudprofileForm input[name="stud_trainingHours"]').val(data.training_hours);
            if (data.sex === "Male") {
               $('#StudprofileForm select[name="user_Sex"]').val("Male");
            } else if (data.sex === "Female") {
               $('#StudprofileForm select[name="user_Sex"]').val("Female");
            }

         }
         //console.log(data)
      },
      error: function(xhr, status, error) {
         console.error('Error fetching data:', error);
      }
   });
}

function resubmitWeeklyReport(weeklyReport_id){
   document.querySelector('#resubmitReport input[name="file_id"]').value = weeklyReport_id;
}

function getUploadLogs(){
   $.ajax({
      url: '../ajax.php?action=getUploadLogs',
      method: 'GET',
      dataType: 'html',
      success: function(response) {
         $('#logsTable_body').html(response);
      },
      error: function(xhr, status, error) {
         console.error('Error fetching data:', error);
      }
   });
}
function getComments(file_id){
   $.ajax({
      url: '../ajax.php?action=getCommentst&file_id=' + file_id,
      method: 'GET',
      dataType: 'html',
      success: function(response) {
         if (response){
            $('#comment_body').html(response);
            $('#chatBox input[name="file_id"]').val(file_id);
            scrollToBottom();
         }
      },
      error: function(xhr, status, error) {
         console.error('Error fetching data:', error);
      }
   });
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

function get_WeeklyReports (){
   $.ajax({
      url: '../ajax.php?action=getWeeklyReports',
      method: 'GET',
      dataType: 'html',
      success: function(response) {
         $('#Weeklyreports').html(response);
      },
      error: function(xhr, status, error) {
         console.error('Error fetching data:', error);
      }
   });
}


