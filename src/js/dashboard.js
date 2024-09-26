/*function handleRouting() {
    const path = window.location.pathname;
    const routes = {
        '/ReposyncNarrativeManagementSystem/src/dashboard.php':'dashboardContent.php',
    };
    if (routes[path]) {
        navigate(routes[path]);
        act_tab('dashboard');
    } else {
        handle404();
    }
}

 */


let oldScripts = [];

navigate('dashboardContent.php')
act_tab('dashboard');
let domScript = ['dashboardContent.js']
for (let i = 0; i< domScript.length; i++){
    const scriptTag = document.createElement('script');
    scriptTag.src ='js/' + domScript[i];
    document.body.appendChild(scriptTag);
    oldScripts.push(domScript[i]);

}




function navigate(page) {
    fetch(page, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('dashboard_main_content').innerHTML = html;
            get_studenUsertList();


            getProfileInfo();




            if (document.getElementById('NewNote')){
                document.getElementById('NewNote').addEventListener('click', function (){
                    document.getElementById('action_type').value = ''
                    document.getElementById('announcementID').value = '';
                    document.getElementById('NotesForm').reset();

                });
            }



            if (document.getElementById('profileForm')){
                document.getElementById('profileImg').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('selectedProfile').src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });

            }
            const ctx = document.getElementById('myChart')
            if (ctx) {
                ctx.getContext('2d');
                renderChart(ctx);
            }




        })
        .catch(error => console.error('Error fetching content:', error));
}


function dashboard_tab(id , newScripts){
    let tab = document.getElementById(id);
    if(tab.id === 'dashboard_narrative'){
        navigate('manageNarrativeReports.php');
    } else if (tab.id === 'dashboard_ReviewUploadNarrative'){
        navigate('manageUploadNarratives.php');
    }  else if (tab.id === 'dashboard'){
        navigate('dashboardContent.php');
    } else if (tab.id === 'adviserNotes'){
        navigate('manageAdviserNote.php')
    }else if (tab.id === 'notesReq'){
        navigate('notesReqmanage.php')
    } else if (tab.id === 'schedule&Act'){
        navigate('manamgeAct&Sched.php')
    } else if (tab.id === 'dashBoardWeeklyReport'){
        navigate('manageWeeklyReport.php')
    }else if (tab.id === 'stud_list'){
        navigate('manageStudent.php');
        document.getElementById('UserSubmenu').classList.remove('hidden');
    }else if (tab.id === 'adv_list'){
        navigate('manageAdvisers.php')
    }else if (tab.id === 'dashBoardProg_n_Section'){
        navigate('ManageProgSec.php');
    }else if (tab.id === 'pendingNarrativeReqCount'){
        navigate('manageUploadNarratives.php');
    }else if (tab.id === 'declinedNarrativeReqCount'){
        navigate('manageUploadNarratives.php');
    }else if (tab.id === 'account_archived'){
        navigate('manageArchive.php');
    }else if (tab.id === 'profile'){
        navigate('manage_dhshboardProfile.php');
    }else if (tab.id === 'accountInfo'){
        navigate('manage_accountInformation.php');
    }
    act_tab(tab.id);
   if(tab.id === 'adviserNotesReq'){
        act_tab('notesReq');
        navigate('notesReqmanage.php')
       document.getElementById('AnnouncementSubmenu').classList.remove('hidden');
    }
   if(tab.id === 'adviserNotesCard'){
        act_tab('adviserNotes');
        navigate('manageAdviserNote.php')
       console.log(1123)
       document.getElementById('AnnouncementSubmenu').classList.remove('hidden');
    }
    else if (tab.id === 'dshbweeklyReport'){
        navigate('manageWeeklyReport.php');
        act_tab('dashBoardWeeklyReport');
    }
    else if (tab.id === 'dshbuploadNarrativeReq'){
        navigate('manageUploadNarratives.php');
        act_tab('dashboard_ReviewUploadNarrative');
    }



    if (oldScripts.length > 0){

        const existingScript = document.getElementsByTagName('script');
        for (let i = 0; i < existingScript.length; i++) {
            let scriptSrc = existingScript[i].getAttribute('src');


            if (oldScripts.includes(scriptSrc.replace('js/', ''))) {
                existingScript[i].remove();
            }

        }

    }

    oldScripts = [];

    for (let i = 0; i < newScripts.length; i++) {
        const scriptTag = document.createElement('script');
        scriptTag.src = 'js/' + newScripts[i];
        document.body.appendChild(scriptTag);
        oldScripts.push(newScripts[i]);

    }


}
async function renderChart(ctx) {
    let activeNarrative = 0;
    let total_activeStudent ;
    let total_activeAdv;
    let totalArchiveStud;
    let totalAchiveAdv;

    try {
        // Fetch the narrative data
        activeNarrative = await getTotalPublihed();
        total_activeStudent = await totalUser(1, 3);
        total_activeAdv = await totalUser(1, 2);
        totalArchiveStud = await totalUser(2, 3);
        totalAchiveAdv = await totalUser(2, 2);
    } catch (error) {
        console.error('Error:', error);
    }

    // Initialize the chart only after activeNarrative has been fetched
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Published Narrative Reports', 'Active student', 'Active adviser', 'Archived adviser', 'Archived student'],
            datasets: [{
                label: '',
                data: [activeNarrative, total_activeStudent, total_activeAdv, totalArchiveStud, totalAchiveAdv], // Use activeNarrative here
                borderWidth: 1,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ]
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false // Hide the legend
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `# of Data: ${context.raw}`;
                        }
                    }
                }
            },
            maintainAspectRatio: false,
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const elementIndex = elements[0].index;
                    const label = myChart.data.labels[elementIndex];
                    if (label === 'Active student') {
                        const elementId = "stud_list";
                        dashboard_tab(elementId);
                        document.getElementById('UserSubmenu').classList.remove('hidden');

                    } else if (label === 'Active adviser') {
                        const elementId = "adv_list";
                        document.getElementById('UserSubmenu').classList.remove('hidden');

                        dashboard_tab(elementId);
                    } else if (label === 'Published Narrative Reports') {
                        const elementId = "dashboard_narrative";
                        dashboard_tab(elementId);
                    }else if (label === 'Archived adviser') {
                        const elementId = "account_archived";
                        dashboard_tab(elementId);
                    }else if (label === 'Archived student') {
                        const elementId = "account_archived";
                        dashboard_tab(elementId);
                    }
                }
            }
        }
    });
}

function act_tab(id){
    const allTabs = document.querySelectorAll('.dashboard_tab'); // Assuming all tabs have the 'tab' class
    allTabs.forEach(tab => {
        tab.classList.add('text-white');
        tab.classList.remove('text-black', 'bg-gray-300', 'rounded');
    });
    let tab = document.getElementById(id);
    tab.classList.add('text-black', 'bg-gray-300', 'rounded');
    tab.classList.remove('text-white');
}



function dashboard_student_NarrativeReports() {
    $.ajax({
        url: '../ajax.php?action=get_narrativeReports&dashboardTable=request',
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


function getTotalPendingUploadNarrative(){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../ajax.php?action=dshbGePendingFinalReports',
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}
function getTotalDeclinedUploadNarrative(){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../ajax.php?action=dshbDeclinedFinalReports',
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}
function getTotalUnreadStudentWeeklyReport(){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../ajax.php?action=dshbPendStudWeeklyReport',
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });

}
function getTotalPendingNotes(){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../ajax.php?action=pendingADVnoteReq',
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}function getTotalPublihed(){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../ajax.php?action=totalPublihedReport',
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}

function totalUser(accountType, userType){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../ajax.php?action=total_Users&userType=' + userType + '&accType=' + accountType,
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}





function editNarrativeReq(narrative_id){
    $.ajax({
        url: '../ajax.php?action=narrativeReportsJson&narrative_id=' + narrative_id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                document.getElementById('dlLink').href='NarrativeReportsPDF/'+ data.narrative_file_name;

                if (document.getElementById('SelectreqStatuses')){
                    $('#declineUploadReason').empty();
                    document.querySelector('#EditNarrativeReportsReqForm select[name="UploadStat"]').value = data.file_status;
                    if (data.file_status  === 'Declined'){
                        $('#declineUploadReason').append('<label class="form-control w-full " id="remarkInput">\n' +
                            '                            <div class="label">\n' +
                            '                                <span class="label-text text-slate-700 font-bold">Remarks</span>\n' +
                            '                            </div>\n' +
                            '                            <input type="text" required  name="remark" value="'+ data.remarks +'" class="input input-error w-full" placeholder="Type here">' +
                            '                        </label>')

                    }

                }
                if (document.getElementById('textStatuses')){
                    $('#ReportUploadStatus').html(data.file_status)
                    $('#ReportUploadRemarks').html(data.remarks);
                }



                let startSchYear = "", endSchYear = "";
                if (data.sySubmitted !== 'N/A') {
                    let years = data.sySubmitted.split(',');
                    startSchYear = years[0].trim();
                    endSchYear = years[1].trim();

                }

                document.querySelector('#EditNarrativeReportsReqForm input[name="startYear"]').value = startSchYear;
                document.querySelector('#EditNarrativeReportsReqForm input[name="endYear"]').value = endSchYear;



                document.querySelector('#EditNarrativeReportsReqForm input[name="narrative_id"]').value = data.narrative_id;

                document.querySelector('#EditNarrativeReportsReqForm input[name="trainingHours"]').value = data.training_hours;
                document.querySelector('#EditNarrativeReportsReqForm input[name="companyName"]').value = data.company_name;

                document.querySelector('#EditNarrativeReportsReqForm input[name="first_name"]').value = data.first_name;
                document.querySelector('#EditNarrativeReportsReqForm input[name="middle_name"]').value = data.middle_name;
                document.querySelector('#EditNarrativeReportsReqForm input[name="last_name"]').value = data.last_name;
                document.querySelector('#EditNarrativeReportsReqForm input[name="school_id"]').value = data.stud_school_id;
                document.querySelector('#EditNarrativeReportsReqForm select[name="program"]').value = data.program;
                document.querySelector('#EditNarrativeReportsReqForm select[name="section"]').value = data.section;
                if (data.sex === "Male") {
                    document.querySelector('#EditNarrativeReportsReqForm input[name="stud_Sex"][value="Male"]').checked = true;
                } else if (data.sex === "Female") {
                    document.querySelector('#EditNarrativeReportsReqForm input[name="stud_Sex"][value="Female"]').checked = true;
                }
                document.querySelector('#EditNarrativeReportsReqForm select[name="ojt_adviser"]').value = data.OJT_adviser_ID;
                document.querySelector('#EditNarrativeReportsReqForm input[name="ojt_adviser"]').value = data.OJT_adviser_ID;
                $('#EditNarrativeReportsReqForm input[type="file"][name="final_report_file"]').val('');

            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}



function editUserStud_Info(user_id) {
    $.ajax({
        url: '../ajax.php?action=getStudInfoJson&data_id=' + user_id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                $('#EditStudentForm input[name="user_Fname"]').val(data.first_name);
                $('#EditStudentForm input[name="user_Mname"]').val(data.middle_name);
                $('#EditStudentForm input[name="user_Lname"]').val(data.last_name);
                $('#EditStudentForm input[name="user_address"]').val(data.address);
                $('#EditStudentForm input[name="contactNumber"]').val(data.contact_number);
                $('#EditStudentForm input[name="school_id"]').val(data.school_id);
                $('#EditStudentForm input[name="user_Email"]').val(data.email);
                $('#EditStudentForm input[name="stud_compName"]').val(data.company_name);
                $('#EditStudentForm input[name="stud_TrainingHours"]').val(data.training_hours);
                $('#EditStudentForm input[name="user_id"]').val(data.user_id);
                $('#deactivate_stud_acc').attr('data-user_id', data.user_id);
                $('#EditStudentForm select[name="stud_Program"]').val(data.program_id);
                $('#EditStudentForm select[name="stud_Section"]').val(data.section_id);
                $('#EditStudentForm select[name="stud_adviser"]').val(data.adviser_id);
                if (data.sex === "Male") {
                    $('#EditStudentForm input[name="user_Sex"][value="Male"]').prop('checked', true);
                } else if (data.sex === "Female") {
                    $('#EditStudentForm input[name="user_Sex"][value="Female"]').prop('checked', true);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function get_studenUsertList (){
    $.ajax({
        url: '../ajax.php?action=getStudentsList',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#studentsList').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}
function deactivate_account(id, modal_id){

    $.ajax({
        url: '../ajax.php?action=deactivate_account&data_id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response){
               if (parseInt(response) === 1){
                   closeModalForm(modal_id);
                   Alert('notifBox', 'User account has been archived', 'warning');
                   get_studenUsertList();
               }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
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


                $("#selectedProfile").attr("src", profPath);

                $('#profileForm input[name="user_Fname"]').val(data.first_name);
                $('#profileForm input[name="user_Mname"]').val(data.middle_name);
                $('#profileForm input[name="user_Lname"]').val(data.last_name);
                $('#profileForm input[name="user_address"]').val(data.address);
                $('#profileForm input[name="contactNumber"]').val(data.contact_number);
                if (data.sex === "Male") {
                    $('#profileForm input[name="user_Sex"][value="Male"]').prop('checked', true);
                } else if (data.sex === "Female") {
                    $('#profileForm input[name="user_Sex"][value="Female"]').prop('checked', true);
                }

            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}


function removeTrashButton() {
    $('#trashAnnouncementBtn').remove();
}
function removeStatusBoxContent(){
    $('#status_Box').empty();
}










