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

