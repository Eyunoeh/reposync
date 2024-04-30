function handleRouting() {
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
function navigate(page) {
    fetch(page, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Add a custom header
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('dashboard_main_content').innerHTML = html;
            dashboard_student_NarrativeReports();
        })
        .catch(error => console.error('Error fetching content:', error));
}

function handle404() {
    window.location.href = '/404.php';
}
function dashboard_tab(id){
    let tab = document.getElementById(id);
    if(tab.id === 'dashboard_narrative'){
        navigate('manageNarrativeReports.php');
    } else if (tab.id === 'dashboard'){
        navigate('dashboardContent.php');
    } else if (tab.id === 'adviserNotes'){
        navigate('manageAdviserNote.php')
    }else if (tab.id === 'schedule&Act'){
        navigate('manamgeAct&Sched.php')
    } else if (tab.id === 'dashBoardWeeklyReport'){
        navigate('manageWeeklyReport.php')
    }else if (tab.id === 'stud_list'){
        navigate('manageStudent.php')
    }else if (tab.id === 'adv_list'){
        navigate('manageAdviserNote.php')
    }else if (tab.id === 'admin_list'){
        navigate('manageAdmin.php')
    }
    act_tab(tab.id);
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


function dropdown(id) {
    let dropdown_tab = document.getElementById(id);
    dropdown_tab.classList.toggle('hidden');
}

function openModalForm(modal_id){
    let modal = document.getElementById(modal_id);
    modal.open = true;
}
function closeModalForm(modal_id) {
    let modal = document.getElementById(modal_id);
    modal.open = false;
}
function add_loader(id){
    let loader = document.getElementById(id);
    loader.classList.remove('hidden');
}
function remove_loader(id){
    let loader = document.getElementById(id);

    loader.classList.add('hidden');
}
function enable_button(btn_id){
    let btn = document.getElementById(btn_id);
    btn.classList.remove('hidden')
}
function disable_button(btn_id){
    let btn = document.getElementById(btn_id);
    btn.classList.add('hidden');

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
function editNarrative(narrative_id){
    $.ajax({
        url: '../ajax.php?action=narrativeReportsJson&narrative_id=' + narrative_id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                document.querySelector('#EditNarrativeReportsForm input[name="first_name"]').value = data.first_name;
                document.querySelector('#EditNarrativeReportsForm input[name="last_name"]').value = data.last_name;
                document.querySelector('#EditNarrativeReportsForm input[name="school_id"]').value = data.stud_school_id;
                document.querySelector('#EditNarrativeReportsForm select[name="program"]').value = data.program;
                document.querySelector('#EditNarrativeReportsForm input[name="section"]').value = data.section;
                if (data.sex === "Male") {
                    document.querySelector('#EditNarrativeReportsForm input[name="stud_Sex"][value="Male"]').checked = true;
                } else if (data.sex === "Female") {
                    document.querySelector('#EditNarrativeReportsForm input[name="stud_Sex"][value="Female"]').checked = true;
                }

                document.querySelector('#EditNarrativeReportsForm input[name="ojt_adviser"]').value = data.OJT_adviser;
                document.querySelector('#EditNarrativeReportsForm input[name="narrative_id"]').value = data.narrative_id;
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}




window.onload = handleRouting;

