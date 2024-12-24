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







dashboard_tab('dashboard')



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



        })
        .catch(error => console.error('Error fetching content:', error));
}


async function dashboard_tab(id) {
    const tab = document.getElementById(id);

    // Mapping tab IDs to their corresponding navigation and additional actions
    const tabActions = {
        'dashboard_narrative': {
            page: 'manageNarrativeReports.php',
            afterNavigate: () => {
                act_tab(tab.id)
                narrativeReportsTree().then(() => {
                    treeListener();
                });



            }
        },
        'dashboard_ReviewUploadNarrative': {
            page: 'manageUploadNarratives.php',
            afterNavigate: () => {
                act_tab(tab.id)
                getStudSubmittedNarratives();
                convertToFlibookLister()


            }
        },
        'dashboard': {
            page: 'dashboardContent.php',
            afterNavigate: () => {
                act_tab(tab.id);
                loadDashboardJS();
                (async () => {
                    let response = await user_info();
                    if (response.data.user_type === 'admin'){
                        renderChart('NarrativeReportChart', 'adminNarrative', 'Total narrative report')
                        renderChart('UserChart', 'Users', 'User summary')
                    }else {
                        renderChart('NarrativeReportChart', 'adviserNarrative','Total Narrative Report Submissions by Section')

                    }

                })()
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
            afterNavigate: () =>{
                act_tab('dashboard_ReviewUploadNarrative')
                getStudSubmittedNarratives();
                convertToFlibookLister()
            }
        },
        'declinedNarrativeReqCount': {
            page: 'manageUploadNarratives.php',
            afterNavigate: () => {
                act_tab('dashboard_ReviewUploadNarrative')
                getStudSubmittedNarratives();
                convertToFlibookLister()
            }
        },
        'account_archived': {
            page: 'manageArchive.php',
            afterNavigate: () => {
                renderTotalArchive();
                act_tab(tab.id)
            }
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
                getAdvNotes();
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
                updateUserInfo();
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
}

function getTotalPublihed(file_status = 3){
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../ajax.php?action=totalPublihedReport&file_status=' + file_status,
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





