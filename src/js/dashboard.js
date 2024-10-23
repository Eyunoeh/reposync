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

let total_records = 0;







navigate('dashboardContent.php')
act_tab('dashboard');
let domScript = ['dashboardContent.js']
for (let i = 0; i< domScript.length; i++){
    const scriptTag = document.createElement('script');
    scriptTag.src ='js/' + domScript[i];
    document.body.appendChild(scriptTag);
    oldScripts.push(domScript[i]);

}


async function navigate(page) {
    return fetch(page, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('dashboard_main_content').innerHTML = html;



            resetDataTable()

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


async function dashboard_tab(id) {
    const tab = document.getElementById(id);

    // Mapping tab IDs to their corresponding navigation and additional actions
    const tabActions = {
        'dashboard_narrative': {
            page: 'manageNarrativeReports.php',
            afterNavigate: () => act_tab(tab.id)
        },
        'dashboard_ReviewUploadNarrative': {
            page: 'manageUploadNarratives.php',
            afterNavigate: () => act_tab(tab.id)
        },
        'dashboard': {
            page: 'dashboardContent.php',
            afterNavigate: () => {
                act_tab(tab.id);
                loadDashboardJS();
            }
        },
        'adviserNotes': {
            page: 'manageAdviserNote.php',
            afterNavigate: () => {
                act_tab(tab.id)
                get_dashBoardnotes();
            }
        },
        'notesReq': {
            page: 'notesReqmanage.php',
            afterNavigate: () => {
                act_tab(tab.id);
                getAdvNotes();
                advNoteReqEventListener();
            }
        },
        'schedule&Act': {
            page: 'manamgeAct&Sched.php',
            afterNavigate: () => {
                act_tab(tab.id);
                getActivitiesAndSched()
                act_n_schedForm_inp_lstner();

            }
        },
        'dashBoardWeeklyReport': {
            page: 'manageWeeklyReport.php',
            afterNavigate: () => {
                act_tab(tab.id);
                renderWeeklyJournaltbl();
            }
        },
        'stud_list': {
            page: 'manageStudent.php',
            afterNavigate: () => {
                act_tab(tab.id);
                get_studentUserList();
                document.getElementById('UserSubmenu').classList.remove('hidden');
            }
        },
        'adv_list': {
            page: 'manageAdvisers.php',
            afterNavigate: () => {
                render_AdvUsertList();
                act_tab(tab.id);
                document.getElementById('UserSubmenu').classList.remove('hidden');
            }
        },
        'dashBoardProg_n_Section': {
            page: 'ManageProgSec.php',
            afterNavigate: () => {
                getPrograms();
                getYrSec();
                act_tab(tab.id);
            }
        },
        'pendingNarrativeReqCount': {
            page: 'manageUploadNarratives.php',
            afterNavigate: () => act_tab(tab.id)
        },
        'declinedNarrativeReqCount': {
            page: 'manageUploadNarratives.php',
            afterNavigate: () => act_tab(tab.id)
        },
        'account_archived': {
            page: 'manageArchive.php',
            afterNavigate: () => act_tab(tab.id)
        },
        'profile': {
            page: 'manage_dhshboardProfile.php',
            afterNavigate: () => act_tab(tab.id)
        },
        'accountInfo': {
            page: 'manage_accountInformation.php',
            afterNavigate: () => act_tab(tab.id)
        },
        'adviserNotesReq': {
            page: 'notesReqmanage.php',
            afterNavigate: () => {
                act_tab('notesReq');
                get_dashBoardnotes();
                document.getElementById('AnnouncementSubmenu').classList.remove('hidden');
            }
        },
        'adviserNotesCard': {
            page: 'manageAdviserNote.php',
            afterNavigate: () => {
                act_tab('adviserNotes');
                document.getElementById('AnnouncementSubmenu').classList.remove('hidden');
                get_dashBoardnotes();
            }
        },
        'dshbweeklyReport': {
            page: 'manageWeeklyReport.php',
            afterNavigate: () => {
                act_tab('dashBoardWeeklyReport')
                renderWeeklyJournaltbl()
            }

        },
        'dshbuploadNarrativeReq': {
            page: 'manageUploadNarratives.php',
            afterNavigate: () => act_tab('dashBoardWeeklyReport')
        }
    };

    // Execute the navigation if the tab ID matches an action
    if (tabActions[tab.id]) {
        const { page, afterNavigate } = tabActions[tab.id];
        await navigate(page);
        afterNavigate();
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
                    } else if (label === 'Active adviser') {
                        const elementId = "adv_list";
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

function getProfileInfo() {
    (async () => {
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

                $('#side_tabName').html(data.first_name + ' ' + data.last_name  + ' - ' + data.user_type.toUpperCase());
                $("#selectedProfile").attr("src", profPath);
                $('#profileForm input[name="user_Fname"]').val(data.first_name);
                $('#profileForm input[name="user_Mname"]').val(data.middle_name);
                $('#profileForm input[name="user_Lname"]').val(data.last_name);
                $('#profileForm input[name="user_address"]').val(data.address);
                $('#profileForm input[name="contactNumber"]').val(data.contact_number);

                // Handle sex radio buttons
                if (data.sex === "male") {
                    $('#profileForm input[name="user_Sex"][value="male"]').prop('checked', true);
                } else if (data.sex === "female") {
                    $('#profileForm input[name="user_Sex"][value="female"]').prop('checked', true);
                }
            } else {
                console.error('Error: Unexpected response format or no data');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    })();

}



function deactivate_account(id, modal_id, newData){
    $.ajax({
        url: '../ajax.php?action=deactivate_account&data_id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.response === 1){ // Only need to check this line
                closeModalForm(modal_id);
                Alert('notifBox', 'User account has been archived', 'warning');
                window[newData]()

            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}


function renderDeacAccLink(modal, refresh_tbl_data){
    $('#deaccSectionModal').html(`
        <dialog id="deactivate_accModal" class="modal bg-black bg-opacity-40">
            <div class="card bg-slate-50 w-[80vw] absolute top-10 sm:w-[30rem] max-h-[35rem] flex flex-col text-slate-700">
                <div class="card-title sticky">
                    <h3 class="font-bold text-center text-lg p-5">Are you sure you want to deactivate this OJT Adviser account?</h3>
                </div>
                <div class="p-4 w-full flex justify-evenly">
                    <a id="deactivate_acc" class="btn btn-error w-1/4" 
                       onclick="closeModalForm('deactivate_accModal');
                                deactivate_account(this.getAttribute('data-user_id'), '${modal}', '${refresh_tbl_data}')">
                        Deactivate
                    </a>
                    <a class="btn btn-info w-1/4" onclick="closeModalForm('deactivate_accModal')">Close</a>
                </div>
            </div>
        </dialog>
    `);

    $('#deactSectionLink').html(`
        <a class="transition-all text-error font-bold font-sans cursor-pointer text-end pr-6 m-3 hover:opacity-50 active:text-slate-500" 
           onclick="openModalForm('deactivate_accModal')">
           Deactivate account? 
        </a>
    `);
}


function removeTrashButton() {
    $('#trashAnnouncementBtn').remove();
}
function removeStatusBoxContent(){
    $('#status_Box').empty();
}

async function loadStudentprogSecDropdown(adv_id) {
    let adv_list = await getAdv_list();

    let prog_yearSec = adv_list.data.reduce((acc, adviser) => {
        let { user_id, program_code, program_id, year, section, year_sec_Id } = adviser;
        if (!acc[user_id]) {
            acc[user_id] = {
                adviser_id: user_id,
                program: program_code,
                program_id: program_id,
                yr_sec: []
            };
        }
        acc[user_id].yr_sec.push({ year, section, year_sec_Id });
        return acc;
    }, {});

    let program_option = ``;
    let yr_sec_option = ``;

    if (prog_yearSec[adv_id]) {
        program_option += `<option value="${prog_yearSec[adv_id].program_id}" selected>${prog_yearSec[adv_id].program}</option>`;
        yr_sec_option = prog_yearSec[adv_id].yr_sec.map(yearsec =>
            `<option value="${yearsec.year_sec_Id}">${yearsec.year} ${yearsec.section}</option>`
        ).join('');
    }
    $('#stud_Program').html(program_option);
    $('#stud_xlsProgram').html(program_option);
    $('#stud_Section').html(yr_sec_option);
    $('#stud_xlsSection').html(yr_sec_option);
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


