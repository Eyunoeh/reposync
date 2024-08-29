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
function resetAlertBox(container) {
    $(`#${container}`).empty();
}

function Alert(container, message, type) {
    let alertTypes = ['info', 'success', 'warning', 'error'];

    let alertIcons = {
        info: `
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                class="h-6 w-6 shrink-0 stroke-current">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>`,
        success: `
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 shrink-0 stroke-current"
                fill="none"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>`,
        warning: `
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 shrink-0 stroke-current"
                fill="none"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>`,
        error: `
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 shrink-0 stroke-current"
                fill="none"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>`
    };

    let alertType = alertTypes.includes(type) ? type : 'info';
    let alertIcon = alertIcons[alertType];

    let notification = `    
    <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div role="alert" class="alert alert-${alertType}">
            ${alertIcon}
            <span>${message}</span>
        </div>
    </div>`;

    $(`#${container}`).html(notification);

    setTimeout(function () {
        resetAlertBox(container);
    }, 5000);
}

