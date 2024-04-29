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
    } else if (tab.id === 'announcement'){
        navigate('manageAnnouncement.php')
    }else if (tab.id === 'dashBoardWeeklyReport'){
        navigate('manageWeeklyReport.php')
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
    btn.removeAttribute("disabled");
}
function disable_button(btn_id){
    let btn = document.getElementById(btn_id);
    btn.setAttribute("disabled", "disabled");
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
                document.querySelector('#EditNarrativeReportsForm input[name="program"]').value = data.program;
                document.querySelector('#EditNarrativeReportsForm input[name="section"]').value = data.section;
                document.querySelector('#EditNarrativeReportsForm input[name="ojt_adviser"]').value = data.OJT_adviser;
                document.querySelector('#EditNarrativeReportsForm input[name="narrative_id"]').value = data.narrative_id

            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}



window.onload = handleRouting;

