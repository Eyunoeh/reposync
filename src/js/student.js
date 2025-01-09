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

   }else if (e.target.id === 'resubmitReportForm'){
      endpoint = 'resubmitReport'
      modal = 'resubmitReport';
      alertType = 'success';
      alertMessage = 'Weekly journal has been resubmitted';
      loadData.push(get_WeeklyReports)

   }
   else if (e.target.id === 'NarrativeReportForm'){

      if (e.target.querySelector('input[name="NarraActType"]').value === 'Edit') {

         endpoint = 'editFinalReport';
         alertMessage = 'Narrative report has been updated!';
         alertType = 'info';
      }else{
         endpoint = 'newFinalReport'
         alertMessage = 'New narrative report has been submitted! Please wait for adviser approval';
         alertType = 'success';
      }


      loader_id = 'narrativeSubmitLoader';
      submit_btn = 'NarrativeSubmit';


      add_loader(loader_id);

      disable_button(submit_btn)



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

            submit_btn && enable_button(submit_btn);
            loader_id && remove_loader(loader_id);
            modal && closeModalForm(modal)

            loadData.length && loadData.forEach(func => func());



         } else {
            Alert(alertContainer,response.message, 'warning')
            console.log(response)
         }


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
               profPath = 'userProfile/prof.jpg';
            } else {
               profPath = 'userProfile/' + data.profile_img_file;
            }

            updateUserInfo();

            $("#selectedProfile").attr("src", profPath);

            $('#StudprofileForm input[name="user_Fname"]').val(data.first_name);
            $('#StudprofileForm input[name="user_Mname"]').val(data.middle_name);
            $('#StudprofileForm input[name="user_Lname"]').val(data.last_name);
            $('#StudprofileForm input[name="user_address"]').val(data.address);
            $('#StudprofileForm input[name="contactNumber"]').val(data.contact_number);
            $('#StudprofileForm input[name="stud_OJT_center"]').val(data.ojt_center);
            $('#StudprofileForm input[name="stud_ojtContact"]').val(data.ojt_contact);
            $('#StudprofileForm input[name="OJT_started"]').val(data.OJT_started);
            $('#StudprofileForm input[name="OJT_ended"]').val(data.OJT_ended);
            $('#StudprofileForm input[name="studInfo"]').val(data.studInfo);

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

function resubmitWeeklyReport(weeklyReport_id, week) {
   document.querySelector('#resubmitReport input[name="file_id"]').value = weeklyReport_id;

   let [startDateStr, endDateStr] = week.split(" - ");
   let startWeekDate = formatDate(startDateStr);
   let endWeekDate = formatDate(endDateStr);
   console.log(startWeekDate);
   console.log(endWeekDate);

   $('#resubmitReport input[type="date"][name="startWeek"]').val(startWeekDate);
   $('#resubmitReport input[type="date"][name="endWeek"]').val(endWeekDate);
}

async function getUploadLogs() {

   try {
      const response = await $.ajax({
         url: '../ajax.php?action=getUploadLogs',
         method: 'GET',
         dataType: 'json'
      });


      let table_data_logs = '';
      const logs = response.data;

      if (logs.length > 0) {
         logs.forEach(log => {
            const filename = log.weeklyFileReport;

            const match = filename.match(/week_([0-9]+)\.pdf/);
            const weekNumber = match ? parseInt(match[1], 10) : '';


            const formattedWeek = weekNumber ? `Week ${weekNumber}` : '';

            const formattedDateTime = formatDateTime(log.activity_date);

            table_data_logs += `
               <tr class="border-b border-dashed last:border-b-0">
                  <td class="p-3 pr-0 ">
                     <span class=" text-light-inverse text-md/normal">${log.week}</span>
                  </td>
                  <td class="p-3 pr-0 ">
                     <span class=" text-light-inverse text-md/normal">${formattedDateTime}</span>
                  </td>
                  <td class="p-3 pr-0 ">
                     <span class=" text-light-inverse text-md/normal">${log.activity_type.charAt(0).toUpperCase() + log.activity_type.slice(1)}</span>
                  </td>
               </tr>`;
         });
         $('#logsTable_body').html(table_data_logs);
         $('#tableNoRes').empty();
      }else {
         $('#tableNoRes').html(`  <p class="text-sm text-slate-700 font-sans">No activity log</p>`)

      }



   } catch (error) {
      console.error('Error fetching data:', error);
   }
}





async function get_WeeklyReports() {
   try {
      const response = await $.ajax({
         url: '../ajax.php?action=getWeeklyReports',
         method: 'GET',
         dataType: 'json'
      });
      let weeklyJournalTable = '';
      if (response.response === 1){


        let  weeklyJournal = response.data;
         let counter = 1
        weeklyJournal.forEach(journal => {


           let journalStatuses = {pending: ['text-warning', 'Unread'],
              revision: ['text-info','With Revision'],
              approved: ['text-success', 'Approved']}


           weeklyJournalTable += `<tr class="border-b border-dashed last:border-b-0">

                                <td class="p-3 pr-0 ">
                                    <span class=" text-light-inverse text-md/normal">${counter}</span>
                                </td><td class="p-3 pr-0 ">
                                    <span class=" text-light-inverse text-md/normal"> ${journal.week}</span>
                                </td>

                                <td class="p-3 pr-0 ">
                                    <span class="${journalStatuses[journal.upload_status][0]} text-light-inverse text-md/normal">${journalStatuses[journal.upload_status][1]}</span>
                                </td>
                                <td class="p-3 pr-0 " >
                                    <div class="indicator hover:cursor-pointer" data-report-comment-id="${journal.file_id}" 
                                    onclick="openModalForm('comments'); getComments(this.getAttribute('data-report-comment-id'));
                                    $('#sendCommentbtn').attr('file_id', ${journal.file_id})
">
                                        <span class="indicator-item badge badge-neutral"  data-journal-comment-id="3" id="journal_comment_2">${journal.totalJournalComment}</span>
                                        <a class=" text-light-inverse text-md/normal"><i class="fa-regular fa-comment"></i></a>
                                    </div>
                                </td>
                                <td class="p-3 pr-0  text-end">`;
           if (['pending', 'revision'].includes(journal.upload_status)){
              weeklyJournalTable += `<div class="tooltip tooltip-bottom" data-tip="Resubmit">
                                        <a class="text-light-inverse text-md/normal mb-1 hover:cursor-pointer 
                                transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"  data-report_id="${journal.file_id}" onclick="openModalForm('resubmitReport');resubmitWeeklyReport(this.getAttribute('data-report_id'),' ${journal.week}')"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </div>`

           }
           weeklyJournalTable += `<div  class="tooltip tooltip-bottom"  data-tip="View">
                                        <a href="StudentWeeklyReports/${journal.weeklyFileReport}" target="_blank" class=" text-light-inverse text-md/normal mb-1 hover:cursor-pointer 
                                    transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"  ><i class="fa-regular fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>`

           counter ++
        })
         $('#tableNoRes').empty();
      }else{
         $('#tableNoRes').html(`  <p class="text-sm text-slate-700 font-sans">${response.message}</p>`)
      }


      $('#Weeklyreports').html(weeklyJournalTable);
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
      $('#tableNoRes').html(`
                    <p class="text-sm text-slate-700 font-sans">No submitted narrative report</p>`);
      $('#SubmitnewBtnContainer').html(`<button class="btn btn-neutral btn-outline" onclick="resetNarratveFormModal();
    closeModalForm('NarrativeReportmodal');openModalForm('NarrativeReportmodalForm')">Submit new</button>
`)

   }
   else {
      narratives.forEach(narrative => {
         let startingAC = narrative.ayStarting
         let endingAC =  narrative.ayEnding


         table_data += `<tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class=" text-light-inverse text-md/normal break-words">${number}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class=" text-light-inverse text-md/normal break-words">${narrative.Semester}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class=" text-light-inverse text-md/normal break-words">${startingAC} - ${endingAC}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class=" text-light-inverse text-md/normal break-words">${narrative.file_status}
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
                                       if (['Approved', 'Archived'].includes(narrative.file_status)){

                                          table_data +=  `
                                    <div class="tooltip tooltip-info   tooltip-bottom" data-tip="View flipbook">
                                      <a href="flipbook.php?view=${narrative.narrative_id}" class=" cursor-pointer text-light-inverse text-md/normal break-words">
                                                                                               
                                                 <i class="fa-regular fa-eye"></i></a>
                                    </div>
                                    `
                                       }else {
                                          table_data += `
                                       <div class="tooltip tooltip-info   tooltip-bottom" data-tip="View pdf">
                                         <a href="NarrativeReportsPDF/${narrative.narrative_file_name}" target="_blank" class=" cursor-pointer text-light-inverse text-md/normal break-words">
                                                            <i class="fa-regular fa-eye"></i></a>
                                       </div>
                                                
                                                 
                                             <div class="tooltip tooltip-bottom" data-tip="Resubmit">
                                               <a onclick="editSubmittedNarrative('${narrative.narrative_id}')" class="font-semibold cursor-pointer text-light-inverse text-md/normal break-words">
                                                  <i class="fa-solid fa-circle-info"></i></a>
                                             </div>`
                                       }
                                      table_data += `
                                       
                                           
                                    </td>
                                </tr>`;
         number++

      })


      if (response.isStudCanSubmitNewNarrative){
         $('#SubmitnewBtnContainer').html(`<button class="btn btn-neutral btn-outline" onclick="resetNarratveFormModal();
    closeModalForm('NarrativeReportmodal');openModalForm('NarrativeReportmodalForm')">Submit new</button>
`)
      }else {
         $('#SubmitnewBtnContainer').empty();
      }


      $('#studuploadedNarrativesTableBody').html(table_data);
      $('#tableNoRes').empty();
   }


}

async function editSubmittedNarrative(narrative_id){
   closeModalForm('NarrativeReportmodal');
   openModalForm('NarrativeReportmodalForm');

   const response = await $.ajax({
      url: '../ajax.php?action=narrativeReportsJson&narrative_id=' + narrative_id,
      method: 'GET',
      dataType: 'json'
   });


   if (response.response === 1){
      let SubmittedNarratives = response.data;


      $('#NarrativeReportForm input[name="NarraActType"]').val('Edit');
      $('#NarrativeReportForm input[name="narrative_id"]').val(narrative_id);
   }
}
function resetNarratveFormModal(){


   $('#NarraActType').val('');
   $('#narrative_id').val('');


   $('#NarrativeReportForm')[0].reset();
}


function WeeklyReportForm_inp_lstner() {
   // Get all startWeek and endWeek inputs
   const startDateInputs = document.querySelectorAll('input[name="startWeek"]');
   const endDateInputs = document.querySelectorAll('input[name="endWeek"]');

   // Loop through each pair of start and end date inputs
   startDateInputs.forEach((startDateInput, index) => {
      const endDateInput = endDateInputs[index]; // Get the corresponding end date input

      // Add the event listener for start date input
      startDateInput.addEventListener('input', function() {
         if (endDateInput.value < startDateInput.value) {
            endDateInput.value = '';
         }
         endDateInput.setAttribute('min', startDateInput.value);
      });

      // Add the event listener for end date input
      endDateInput.addEventListener('input', function() {
         if (startDateInput.value > endDateInput.value) {
            startDateInput.value = '';
         }
      });
   });

   // Example event listener for form reset (if needed)
   document.getElementById('newWeeklyReport').addEventListener('click', function () {
      // Perform form reset or any additional actions
      // document.getElementById('action_type').value = '';
      // document.getElementById('announcementID').value = '';
      // document.getElementById('act_n_schedForm').reset();
   });
}

