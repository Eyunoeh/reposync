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
    fetch(page)
        .then(response => response.text())
        .then(html => {
            document.getElementById('dashboard_main_content').innerHTML = html ;



        })
        .catch(error => console.error('Error fetching content:', error));
}
function handle404() {
    window.location.href = '/404.php';
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

function dropdown(id) {
    let dropdown_tab = document.getElementById(id);
    dropdown_tab.classList.toggle('hidden'); // Toggle the 'hidden' class
}



window.onload = handleRouting;

